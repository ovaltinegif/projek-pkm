<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Sensors
        $sensors = [];
        for ($i = 1; $i <= 5; $i++) {
            $sensors[] = \App\Models\Sensor::create([
                'device_id' => 'ESP-00' . $i,
                'name' => 'Sensor Log Pos ' . $i,
                'latitude' => -6.200000 + (rand(-100, 100) / 10000),
                'longitude' => 106.816666 + (rand(-100, 100) / 10000),
                'is_active' => true,
            ]);
        }

        // 2. Create Logs (Last 24 hours)
        foreach ($sensors as $sensor) {
            for ($j = 0; $j < 24; $j++) {
                $temp = rand(240, 320) / 10;
                $hum = rand(600, 900) / 10;
                $rain = rand(0, 500) / 10;
                $water = rand(0, 100) / 10;
                
                // Simple risk logic for seeding
                $risk = 'aman';
                if ($rain > 20 || $water > 5) $risk = 'bahaya';
                elseif ($temp > 30 || $hum > 80) $risk = 'waspada';

                \App\Models\SensorLog::create([
                    'sensor_id' => $sensor->id,
                    'temperature' => $temp,
                    'humidity' => $hum,
                    'rainfall' => $rain,
                    'water_level' => $water,
                    'risk_level' => $risk,
                    'created_at' => now()->subHours($j),
                ]);
            }
        }
    }
}
