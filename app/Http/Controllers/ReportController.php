<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        if ($schedule = Auth::user()->schedule)
        {
            $modules = $schedule->modules;
            $sessions = $schedule->sessions;
        }
        

        return view('reports.show', compact('modules','sessions'));
    }
}
