<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Sensor;
// use App\Models\AqiData;

// class AdminController extends Controller
// {
//     public function index()
//     {
//         // Fetch all sensors
//         $sensors = Sensor::all();

//         // Fetch recent AQI data with sensor relationship, limited to latest 20 entries
//         $aqiData = AqiData::with('sensor')->latest()->take(20)->get();

//         // Pass data to the dashboard view
//         return view('dashboard', compact('sensors', 'aqiData'));
//     }
// }
