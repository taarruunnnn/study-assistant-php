<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Session;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $modules = [];
        if ($schedule = $user->schedule)
        {
            $sessions = $schedule->sessions;
            
            foreach ($sessions as $session) 
            {
                $date = new Carbon( $session['date']);

                if($date->isToday())
                {
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

    public function complete(Request $request)
    {
        $sessionId = $request['sessionId'];
        $session = Session::find($sessionId);
        $session->status = "completed";
        $session->completed_time = Carbon::now();
        $session->save();

        return $sessionId;
    }

    public function refresh()
    {
        missed_sessions();
        return back();
    }

}
