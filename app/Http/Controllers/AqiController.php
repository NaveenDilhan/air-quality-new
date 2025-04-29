<?php

namespace App\Http\Controllers;

use App\Models\AqiData;
use App\Models\Sensor;
use Illuminate\Http\Request;

class AqiController extends Controller
{
    public function history($sensor_id)
    {
        $sensor = Sensor::findOrFail($sensor_id);
        $aqiData = AqiData::where('sensor_id', $sensor_id)->get();
        $averages = AqiData::where('sensor_id', $sensor_id)
            ->selectRaw('AVG(aqi) as aqi, AVG(co2_level) as co2_level, AVG(pm25_level) as pm25_level')
            ->first();

        return view('history', compact('sensor', 'aqiData', 'averages'));
    }

    public function aqiHistoryApi(Request $request, $sensor_id)
    {
        $query = AqiData::where('sensor_id', $sensor_id);

        // Apply date filters
        if ($request->start_date) {
            $query->whereDate('recorded_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('recorded_at', '<=', $request->end_date);
        }

        // Apply sorting
        if ($request->sort_by && $request->sort_direction) {
            $query->orderBy($request->sort_by, $request->sort_direction);
        } else {
            $query->orderBy('recorded_at', 'desc');
        }

        $data = $query->get();
        $averages = AqiData::where('sensor_id', $sensor_id)
            ->selectRaw('AVG(aqi) as aqi, AVG(co2_level) as co2_level, AVG(pm25_level) as pm25_level')
            ->first();

        return response()->json([
            'data' => $data,
            'averages' => $averages
        ]);
    }
}