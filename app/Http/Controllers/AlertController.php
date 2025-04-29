<?php

namespace App\Http\Controllers;

use App\Models\Alert; // Assuming you have an Alert model
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function alerts()
    {
        $alerts = Alert::orderBy('created_at', 'desc')->get(); // Get the most recent alerts
        return view('alerts', compact('alerts'));
    }
}
