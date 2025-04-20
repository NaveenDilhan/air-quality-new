<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    // Define the table name if it's not following Laravel's naming convention (optional)
    // protected $table = 'sensors';

    // Define the fillable attributes to allow mass assignment
    protected $fillable = [
        'sensor_id', 'location', 'latitude', 'longitude', 'is_active',
    ];

    // Define the attributes that should be cast to native types
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    // You can also add relationships if needed, such as to the `AqiData` model
    public function aqiData()
    {
        return $this->hasMany(AqiData::class);
    }
}

