<?php

namespace App\Http\Controllers;

use App\Models\AqiData;
use Illuminate\Http\Request;

class AqiController extends Controller
{
    /**
     * Display the AQI management page.
     */
    public function index()
    {
        // Your logic for managing AQI data
        return view('aqi-management');
    }

    /**
     * Display the AQI history for a specific sensor.
     */
    public function history($sensor_id)
    {
        if (!is_numeric($sensor_id)) {
            abort(404, 'Invalid sensor ID');
        }

        $records = AqiData::where('sensor_id', $sensor_id)
            ->orderBy('recorded_at', 'desc')
            ->paginate(20);

        return view('history', compact('records', 'sensor_id'));
    }
}