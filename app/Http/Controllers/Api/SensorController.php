<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\SensorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|exists:sensors,device_id',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'rainfall' => 'required|numeric',
            'water_level' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Ambil Sensor berdasarkan Device ID
        $sensor = Sensor::where('device_id', $request->device_id)->first();

        if (!$sensor->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Device is inactive'
            ], 403);
        }

        // 3. Kalkulasi Risiko (Logic dari Plan)
        $riskLevel = $this->calculateRisk(
            $request->temperature,
            $request->humidity,
            $request->water_level,
            $request->rainfall
        );

        // 4. Simpan Log
        $log = SensorLog::create([
            'sensor_id' => $sensor->id,
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
            'rainfall' => $request->rainfall,
            'water_level' => $request->water_level,
            'risk_level' => $riskLevel,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data stored successfully',
            'data' => [
                'risk_level' => $riskLevel,
                'logged_at' => $log->created_at
            ]
        ], 200);
    }

    private function calculateRisk($temp, $humidity, $water_level, $rainfall)
    {
        $score = 0;

        // 1. Analisa Suhu (Bobot 30%)
        if ($temp >= 25 && $temp <= 30) {
            $score += 30; // Suhu ideal nyamuk
        } elseif ($temp > 30) {
            $score += 10;
        }

        // 2. Analisa Kelembaban (Bobot 30%)
        if ($humidity > 75) {
            $score += 30;
        } elseif ($humidity > 60) {
            $score += 15;
        }

        // 3. Analisa Genangan/Hujan (Bobot 40%)
        if ($water_level > 2 || $rainfall > 10) {
            $score += 40;
        } elseif ($water_level > 0 || $rainfall > 0) {
            $score += 20;
        }

        // Tentukan Label
        if ($score >= 80) {
            return 'bahaya';
        } elseif ($score >= 50) {
            return 'waspada';
        } else {
            return 'aman';
        }
    }
}
