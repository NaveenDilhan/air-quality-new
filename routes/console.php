<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Existing example command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ✅ Add this to schedule your AQI simulation command
Schedule::command('simulate:aqidata')->everyTenMinutes();

Schedule::command('aqi:generate-alerts')->everyThirtyMinutes(); 