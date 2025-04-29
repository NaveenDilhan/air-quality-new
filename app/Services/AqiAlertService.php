<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\AqiData;
use App\Models\Sensor;

class AqiAlertService
{
    public function generateAlerts()
    {
        // Fetch the latest AQI data for each sensor
        $sensors = Sensor::all();

        foreach ($sensors as $sensor) {
            // Get the latest AQI data for each sensor
            $latestData = $sensor->aqiData()->latest()->first();

            if ($latestData) {
                // Determine alert level based on AQI
                $alertLevel = $this->getAlertLevel($latestData->aqi);

                // Create an alert if necessary
                if ($alertLevel) {
                    Alert::create([
                        'aqi_data_id' => $latestData->id,
                        'alert_level' => $alertLevel,
                        'message' => "The AQI level is $alertLevel at {$sensor->location}. Current value: {$latestData->aqi}",
                    ]);
                }
            }
        }
    }

    // Get alert level based on AQI value
    private function getAlertLevel($aqi)
    {
        if ($aqi <= 50) {
            return 'Healthy';
        } elseif ($aqi <= 100) {
            return 'Moderate';
        } elseif ($aqi <= 150) {
            return 'Unhealthy';
        } elseif ($aqi > 150) {
            return 'Hazardous';
        }

        return null;
    }
}
