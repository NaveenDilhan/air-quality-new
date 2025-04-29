<?php

namespace App\Http\Controllers;

use App\Models\Alert; 
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function alerts()
    {
        $alerts = Alert::orderBy('created_at', 'desc')->get(); 
        return view('alerts', compact('alerts'));
    }
}
