<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\AqiData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetching the latest AQI data for real-time data chart
        $aqiData = AqiData::latest()->take(4)->get();  // Example: getting the last 4 records
        
        // Fetch the sensors for displaying in the cards
        $sensors = Sensor::all(); // You can adjust this to fetch specific sensors or data

        return view('dashboard', compact('aqiData', 'sensors'));
    }
}
