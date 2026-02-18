<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SensorApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_can_store_sensor_data_successfully()
    {
        // Create a dummy sensor
        $sensor = \App\Models\Sensor::create([
            'device_id' => 'ESP-TEST-001',
            'name' => 'Test Sensor',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'is_active' => true,
        ]);

        $payload = [
            'device_id' => 'ESP-TEST-001',
            'temperature' => 28.5,
            'humidity' => 65.2,
            'rainfall' => 12.5,
            'water_level' => 5.0
        ];

        $response = $this->postJson('/api/sensors/data', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Data stored successfully',
            ]);

        $this->assertDatabaseHas('sensor_logs', [
            'sensor_id' => $sensor->id,
            'temperature' => 28.5,
            'rainfall' => 12.5,
        ]);
    }

    public function test_cannot_store_data_for_invalid_device()
    {
        $payload = [
            'device_id' => 'INVALID-DEVICE', // Not in DB
            'temperature' => 28.5,
            'humidity' => 65.2,
            'rainfall' => 12.5,
            'water_level' => 5.0
        ];

        $response = $this->postJson('/api/sensors/data', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['device_id']);
    }

    public function test_cannot_store_data_with_missing_fields()
    {
        $payload = [
            'device_id' => 'ESP-TEST-001',
            // Missing temperature, humidity etc.
        ];

        $response = $this->postJson('/api/sensors/data', $payload);

        $response->assertStatus(422);
    }

    public function test_risk_level_calculation_danger()
    {
        $sensor = \App\Models\Sensor::create([
            'device_id' => 'ESP-DANGER',
            'name' => 'Danger Zone',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'is_active' => true,
        ]);

        // High rainfall + ideal temp for mosquito + high humidity = danger
        $payload = [
            'device_id' => 'ESP-DANGER',
            'temperature' => 27.0, // Ideal (30 pts)
            'humidity' => 80.0,    // High (>75) (30 pts)
            'rainfall' => 50.0,    // Heavy rain (40 pts)
            'water_level' => 10.0  // Flooded
        ];
        // Total score = 30 + 30 + 40 = 100 (> 80) -> BAHAYA

        $response = $this->postJson('/api/sensors/data', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.risk_level', 'bahaya');

        $this->assertDatabaseHas('sensor_logs', [
            'risk_level' => 'bahaya'
        ]);
    }

    public function test_inactive_sensor_cannot_send_data()
    {
        \App\Models\Sensor::create([
            'device_id' => 'ESP-INACTIVE',
            'name' => 'Inactive Sensor',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'is_active' => false,
        ]);

        $payload = [
            'device_id' => 'ESP-INACTIVE',
            'temperature' => 28.5,
            'humidity' => 65.2,
            'rainfall' => 12.5,
            'water_level' => 5.0
        ];

        $response = $this->postJson('/api/sensors/data', $payload);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Device is inactive']);
    }
}
