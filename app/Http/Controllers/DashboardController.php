<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = schedule_retriever();
        
        $module_list = array();

        if (!empty($data)) {
            foreach ($data as $d) {
                $date = new Carbon($d['start']);
                if ($date->isToday()) {
                    array_push($module_list, $d['title']);
                }
            }
        }

        if ($schedule = Auth::user()->schedule) {
            $modules = $schedule->modules;
            $sessions = $schedule->sessions;

            $total_session_count = count($sessions);
            $finished = count($sessions->where('status', 'completed'));
            $progress = round((($finished/$total_session_count) * 100), 2);

            $missed = count($sessions->where('status', 'failed'));
            $left = count($sessions->where('status', 'incomplete'));
        }

        $quotes_path = Storage::disk('local')->get('public/quotes.json');
        $quotes = json_decode($quotes_path, true);
        $quote = $quotes[rand(0, (count($quotes)-1))];
 
        return view('dashboard', compact('schedule', 'modules', 'module_list', 'progress', 'finished', 'left', 'missed', 'quote'));
    }
}
