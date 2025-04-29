<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\AqiData;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Fetch sensors and AQI data (adjust based on user permissions)
        $sensors = Sensor::all();
        $aqiData = AqiData::with('sensor')->latest()->take(10)->get();

        return view('user-dashboard', compact('sensors', 'aqiData'));
    }
}
?>