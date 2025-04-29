<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Optional: Uncomment if the table name differs from Laravel's convention ('sensors').
     */
    // protected $table = 'sensors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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