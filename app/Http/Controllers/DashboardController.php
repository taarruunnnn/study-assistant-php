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
        $this->middleware('is_user');
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

        $toarchive = false;

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

            if ($total_session_count > 0){
                $finished = count($sessions->where('status', 'completed'));
                $progress = round((($finished/$total_session_count) * 100), 2);

                $hours = $finished * 2;

                $missed = count($sessions->where('status', 'failed'));
                $left = count($sessions->where('status', 'incomplete'));

                $missed_percentage = ($missed / $total_session_count) * 100;
                $missed_percentage = (int) $missed_percentage;
            } else {
                $finished = $progress = $missed = $left = $missed_percentage = 0;
            }

            $today = Carbon::today();
            $schedule_end = new Carbon($schedule->end);
        
            if ($today->greaterThanOrEqualTo($schedule_end)) {
                $toarchive = true;
            }
        }

        $quotes_path = Storage::disk('local')->get('public/quotes.json');
        $quotes = json_decode($quotes_path, true);
        $quote = $quotes[rand(0, (count($quotes)-1))];

        $notifyComp = false;
        $completed_modules = $user->completed_modules->where('grade', null);

        if (Carbon::now()->isWeekend() && count($completed_modules) > 0){
            $notifyComp = true;
        }

 
        return view(
            'dashboard', compact(
                'schedule', 'modules', 'module_list', 'progress', 'finished', 'hours', 'left', 'missed', 'missed_percentage', 'quote', 'notifyComp', 'toarchive'
            )
        );
    }
}
