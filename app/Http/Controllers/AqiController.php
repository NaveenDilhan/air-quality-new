<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AqiController extends Controller
{
    public function index()
    {
        // Your logic for managing AQI data
        return view('aqi-management');
    }
}