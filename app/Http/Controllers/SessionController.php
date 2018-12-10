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
        $sessions = $user->schedule->sessions;
        $modules = [];
        
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
        
        return view('schedules.session', compact('modules'));
    }

    public function complete(Request $request)
    {
        $sessionId = $request['sessionId'];
        $session = Session::find($sessionId);
        $session->status = 1;
        $session->save();

        return $sessionId;
    }
}
