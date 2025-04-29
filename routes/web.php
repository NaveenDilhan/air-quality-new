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

// 🏠 Public Welcome Page (Landing page)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// 🧑 User Home (Only for regular authenticated & verified users)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // 🧑‍💻 User Dashboard (separate from admin dashboard)
    Route::get('/user-dashboard', [UserDashboardController::class, 'index'])->name('user-dashboard');
});

// ⚙️ Settings Pages (for all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::redirect('/settings', '/settings/profile');  // Redirect to profile settings

    Volt::route('/settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('/settings/password', 'settings.password')->name('settings.password');
    Volt::route('/settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// 📌 Static Info Pages
Route::view('/map', 'map')->name('map');  // Ensure 'map.blade.php' exists
Route::get('/history/{sensor_id}', [AqiController::class, 'history'])->name('history');  // Ensure this method exists in the controller
Route::view('/privacy', 'privacy')->name('privacy');  // Ensure 'privacy.blade.php' exists
Route::view('/terms', 'terms')->name('terms');  // Ensure 'terms.blade.php' exists

// 🛠️ Admin Dashboard & Management (Requires role: admin)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Volt::route('/sensor-management', 'sensor-management')->name('sensor-management');
        Volt::route('/user-management', 'user-management')->name('user-management');
        Volt::route('/aqi-data-management', 'aqi-data-management')->name('aqi-data-management');
        Volt::route('/alert-management', 'alert-management')->name('alert-management');
    });
});

// 📡 Sensor API (Used by frontend for live data)
Route::get('/api/sensors', [SensorController::class, 'fetchSensors'])
    ->name('api.sensors')
    ->middleware('throttle:60,1');  // Throttle to prevent too many requests in a short time

// 📣 Alerts Page (For showing alerts to users)
Route::get('/alerts', [AlertController::class, 'alerts'])->name('alerts'); // Added the alerts route

// 🔐 Auth Routes
require __DIR__.'/auth.php';  // Ensure your authentication routes are correctly set up
?>