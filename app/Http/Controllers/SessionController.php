<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Session;

use Illuminate\Http\Request;

/**
 * Session controller is used to handle functions
 * related to App\Session
 */
class SessionController extends Controller
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
     * Show Function
     * 
     * Sessions are collected from database
     * and send to user to start a 
     * study timer
     *
     * @return View
     */
    public function show()
    {
        $user = Auth::user();
        $modules = [];
        if ($schedule = $user->schedule) {
            $sessions = $schedule->sessions;
            
            foreach ($sessions as $session) {
                $date = new Carbon($session['date']);

                if ($date->isToday()) {
                    $modules[] = [
                        'id' => $session['id'],
                        'module' => $session['module'],
                        'status' => $session['status']
                    ];
                }
            }
        }

        return view('schedules.session', compact('modules'));
    }

    /**
     * Complete Function
     * 
     * Sessions are marked complete once user
     * finishes studying
     *
     * @param Request $request Request object received via POST
     * 
     * @return int
     */
    public function complete(Request $request)
    {
        $sessionId = $request['sessionId'];
        $session = Session::find($sessionId);
        $session->status = "completed";
        $session->completed_time = Carbon::now();
        $session->save();

        return $sessionId;
    }

}
