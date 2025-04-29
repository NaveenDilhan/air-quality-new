<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sensor;
use App\Models\AqiData;
use Carbon\Carbon;

class GenerateSimulatedAqiData extends Command
{
    protected $signature = 'simulate:aqidata';
    protected $description = 'Generate simulated AQI data for all active sensors';

    public function handle()
    {
        $sensors = Sensor::where('is_active', true)->get();

        foreach ($sensors as $sensor) {
            $co2 = rand(300, 1000); 
            $o2 = rand(190000, 210000) / 10000; 
            $pm25 = rand(5, 150); 
            $pm10 = rand(10, 200); 
            $no2 = rand(10, 200); 
            $so2 = rand(5, 150); 

            // Basic AQI calculation 
            $aqi = intval(($pm25 + $pm10 + $no2 + $so2) / 4);

            AqiData::create([
                'sensor_id' => $sensor->id,
                'co2_level' => $co2,
                'o2_level' => $o2,
                'pm25_level' => $pm25,
                'pm10_level' => $pm10,
                'no2_level' => $no2,
                'so2_level' => $so2,
                'aqi' => $aqi,
                'recorded_at' => Carbon::now(),
            ]);
        }

        $this->info("Simulated AQI data generated for all sensors.");
    }
}