<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    protected $fillable = [
        'sensor_id',
        'temperature',
        'humidity',
        'rainfall',
        'water_level',
        'risk_level',
    ];

    protected $casts = [
        'temperature' => 'float',
        'humidity' => 'float',
        'rainfall' => 'float',
        'water_level' => 'float',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}
