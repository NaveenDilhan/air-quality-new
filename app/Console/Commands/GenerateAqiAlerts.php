<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AqiAlertService;

class GenerateAqiAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aqi:generate-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate alerts based on the AQI data';

    /**
     * Execute the console command.
     *
     * @param AqiAlertService $aqiAlertService
     * @return void
     */
    public function handle(AqiAlertService $aqiAlertService)
    {
        $aqiAlertService->generateAlerts();
        $this->info('Alerts generated successfully!');
    }
}
