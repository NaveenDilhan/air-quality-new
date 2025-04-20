<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\AqiController;
use App\Http\Controllers\UserController;

// Home Route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard Route (Requires Authentication & Verification)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Settings Routes using Volt (Livewire Components)
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Static Page Routes
Route::view('map', 'map')->name('map');
Route::view('privacy', 'privacy')->name('privacy');
Route::view('terms', 'terms')->name('terms');

// Management Routes (Only for Authenticated Users)
Route::middleware(['auth'])->group(function () {
    Route::get('sensor-management', [SensorController::class, 'index'])->name('sensor-management');
    Route::get('user-management', [UserController::class, 'index'])->name('user-management');
    Route::get('aqi-management', [AqiController::class, 'index'])->name('aqi-management');
});

// Sensor API Routes (For AQI Data Fetching)
// Public API route for sensors (for map data fetching)
Route::get('/api/sensors', [SensorController::class, 'fetchSensors'])->name('api.sensors');

// Authentication Routes
require __DIR__.'/auth.php';
