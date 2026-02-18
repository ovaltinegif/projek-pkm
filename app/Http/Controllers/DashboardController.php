<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Summary Cards Data (Today)
        $today = Carbon::today();
        
        $totalSensors = Sensor::count();
        $activeSensors = Sensor::where('is_active', true)->count();
        
        $avgStats = SensorLog::whereDate('created_at', $today)
            ->select(
                DB::raw('AVG(temperature) as avg_temp'),
                DB::raw('AVG(humidity) as avg_hum'),
                DB::raw('AVG(rainfall) as avg_rain'),
                DB::raw('MAX(created_at) as last_update')
            )
            ->first();

        // 2. Risk Distribution (Based on latest log per sensor)
        // This is a bit complex in SQL, for now let's just take all logs from today
        // Or better: get the latest log for each sensor
        $sensors = Sensor::with(['logs' => function($query) {
            $query->latest()->limit(1);
        }])->get();

        $riskCounts = [
            'aman' => 0,
            'waspada' => 0,
            'bahaya' => 0
        ];

        foreach ($sensors as $sensor) {
            if ($sensor->logs->isNotEmpty()) {
                $latestLog = $sensor->logs->first();
                // Check if log is recent (e.g. within 24 hours) - optional, but good for accuracy
                if ($latestLog->created_at->diffInHours(now()) < 24) {
                     $risk = $latestLog->risk_level ?? 'aman';
                     if (isset($riskCounts[$risk])) {
                         $riskCounts[$risk]++;
                     }
                }
            }
        }

        // 3. Recent Logs for Table
        $recentLogs = SensorLog::with('sensor')
            ->latest()
            ->limit(5)
            ->get();

        // 4. Chart Data (Hourly averages for today)
        // Check database driver to ensure compatibility
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $hourExpression = $isSqlite ? "strftime('%H', created_at) as hour" : 'HOUR(created_at) as hour';

        $chartData = SensorLog::whereDate('created_at', $today)
            ->select(
                DB::raw($hourExpression),
                DB::raw('AVG(temperature) as temp'),
                DB::raw('AVG(humidity) as hum')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
            
        return view('dashboard', compact(
            'totalSensors',
            'activeSensors',
            'avgStats',
            'riskCounts',
            'recentLogs',
            'sensors',
            'chartData'
        ));
    }
}
