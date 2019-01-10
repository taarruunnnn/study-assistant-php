<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Session;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'message' => 'Successfully logged in'
        ]);
    }

    public function checkAuth(Request $request)
    {
        if ($request->user())
        {
            return response()->json([
                'message' => 'Logged'
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($schedule = $user->schedule)
        {
            $sessions = $user->schedule->sessions()->get();
            $module_list = array();

            if(!empty($sessions))
            {
                foreach($sessions as $session)
                {
                    $date = new Carbon($session->date);
                    if($date->isToday())
                    {
                        array_push($module_list, array('id' => $session->id, 'module' => $session->module, 'status' => $session->status));
                    }
                }
            }
            else
            {
                $module_list = array();
                array_push($module_list, array('id' => 0, 'module' => 'null', 'status' => 'null'));
            }
        }
        else
        {
            $module_list = array();
            array_push($module_list, array('id' => 0, 'module' => 'null', 'status' => 'null'));
        }
        
        return response()->json([
            'name' => $user->name,
            'sessions' => $module_list
        ]);
    }

    public function getSession($id){
        $session = Session::find($id);
        return response()->json([
            'id' => $id,
            'module' => $session->module,
            'status' => $session->status
        ]);
    }

    public function sessionComplete($id)
    {
        $session = Session::find($id);
        $session->status = "completed";
        $session->completed_time = Carbon::now();
        $session->save();

        return response()->json([
            'message' => 'Completed Session'
        ]);
    }

    public function sessionCheck(Request $request){
        $user = $request->user();
        $count = 0;

        if ($schedule = $user->schedule)
        {
            $sessions = $user->schedule->sessions()->get();

            if(!empty($sessions))
            {
                foreach($sessions as $session)
                {
                    $date = new Carbon($session->date);
                    if($date->isToday() && !($session->status == 'completed'))
                    {
                        $count++;
                    }
                }
            }
        }

        if ($count > 0)
        {
            return response()->json([
                'toStudy' => true
            ]);
        }
        else
        {
            return response()->json([
                'toStudy' => false
            ]);
        }
    }

}
