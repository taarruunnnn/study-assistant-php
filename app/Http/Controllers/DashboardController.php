<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Dashboard Controller is used to handle
 * functions related to the dashboard
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     * Only authenticated users can access its methods
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Index Function
     * 
     * Queries the database for schedule information
     * and sends them to the dashboard view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $data = scheduleRetriever($user);
        
        $module_list = array();

        if (!empty($data)) {
            foreach ($data as $d) {
                $date = new Carbon($d['start']);
                if ($date->isToday()) {
                    array_push($module_list, $d['title']);
                }
            }
        }

        if ($schedule = $user->schedule) {
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
