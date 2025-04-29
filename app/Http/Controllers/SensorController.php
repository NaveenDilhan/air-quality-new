<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // Method for managing sensors
    public function index()
    {
        // Your logic for managing sensors
        return view('sensor-management');
    }

    // Method to fetch sensor data and return as JSON
    public function fetchSensors()
    {
        // Fetching only active sensors with their related AQI data
        $sensors = Sensor::where('is_active', true)->with('aqiData')->get();

        // Mapping sensors data for the map
        $data = $sensors->map(function ($sensor) {
            // Returning necessary data for the map (latest AQI data)
            return [
                'sensor_id' => $sensor->id,
                'location' => $sensor->location,
                'latitude' => $sensor->latitude,
                'longitude' => $sensor->longitude,
                'aqi' => $sensor->aqiData->last()->aqi ?? 'N/A',
                'co2_level' => $sensor->aqiData->last()->co2_level ?? 'N/A',
                'o2_level' => $sensor->aqiData->last()->o2_level ?? 'N/A',
                'pm25_level' => $sensor->aqiData->last()->pm25_level ?? 'N/A',
                'pm10_level' => $sensor->aqiData->last()->pm10_level ?? 'N/A',
                'no2_level' => $sensor->aqiData->last()->no2_level ?? 'N/A',
                'so2_level' => $sensor->aqiData->last()->so2_level ?? 'N/A',
            ];
        });

        // Returning the data as a JSON response
        return response()->json($data);
    }
}