<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\AqiController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------|
| Web Routes                                                              |
|--------------------------------------------------------------------------|
*/


Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');


    Route::get('/user-dashboard', [UserDashboardController::class, 'index'])->name('user-dashboard');
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('/settings', '/settings/profile');  // Redirect to profile settings

    Volt::route('/settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('/settings/password', 'settings.password')->name('settings.password');
    Volt::route('/settings/appearance', 'settings.appearance')->name('settings.appearance');
});


Route::view('/map', 'map')->name('map');  
Route::get('/history/{sensor_id}', [AqiController::class, 'history'])->name('history'); 
Route::view('/privacy', 'privacy')->name('privacy');  
Route::view('/terms', 'terms')->name('terms');  


Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Volt::route('/sensor-management', 'sensor-management')->name('sensor-management');
        Volt::route('/user-management', 'user-management')->name('user-management');
        Volt::route('/aqi-data-management', 'aqi-data-management')->name('aqi-data-management');
        Volt::route('/alert-management', 'alert-management')->name('alert-management');
    });
});


Route::get('/api/sensors', [SensorController::class, 'fetchSensors'])
    ->name('api.sensors')
    ->middleware('throttle:60,1');  


Route::get('/alerts', [AlertController::class, 'alerts'])->name('alerts'); 


require __DIR__.'/auth.php';  
?>