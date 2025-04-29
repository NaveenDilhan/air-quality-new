<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AqiData extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id', 'co2_level', 'o2_level', 'pm25_level', 'pm10_level', 'no2_level', 'so2_level', 'aqi', 'recorded_at',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}