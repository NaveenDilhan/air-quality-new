<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'sensor_id',
        'location',
        'latitude',
        'longitude',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    /**
     * Define the relationship with the AqiData model.
     * A sensor can have many AQI data records.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aqiData()
    {
        return $this->hasMany(AqiData::class, 'sensor_id', 'id');
    }
}