<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = ['aqi_data_id', 'alert_level', 'message'];

    public function aqiData()
    {
        return $this->belongsTo(AqiData::class, 'aqi_data_id');
    }
}
