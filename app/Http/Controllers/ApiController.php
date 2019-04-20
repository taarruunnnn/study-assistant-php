<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Session;
use Illuminate\Support\Carbon;

/**
 * This controller is used to handle all
 * API requests sent to and from the 
 * Android Application
 */
class ApiController extends Controller
{
    /**
     * Login Function
     * 
     * This is used to authenticate users
     *
     * @param Request $request Request object received via GET/POST
     * 
     * @return Response
     */
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ]
        );
        
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json(
                [
                    'message' => 'Incorrect Credentials'
                ], 401
            );
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        return response()->json(
            [
                'access_token' => $tokenResult->accessToken,
                'message' => 'Successfully logged in'
            ]
        );
    }

    /**
     * Check Authentication Function
     * 
     * This function is used by the Android App
     * via API, to constantly check if the user
     * is authenticated
     *
     * @param Request $request Request object received via GET/POST
     * 
     * @return Response
     */
    public function checkAuth(Request $request)
    {
        if ($request->user()) {
            return response()->json(
                [
                    'message' => 'Logged'
                ]
            );
        }
    }

    /**
     * Logout Function
     *
     * This function logs out users
     * 
     * @param Request $request Request object received via GET/POST
     * 
     * @return Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(
            [
                'message' => 'Successfully logged out'
            ]
        );
    }

    /**
     * Dashboard Function
     * 
     * This function displays basic user information via
     * the API
     *
     * @param Request $request
     * 
     * @return Response
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $data = scheduleRetriever($user);
        
        
        if ($schedule = $user->schedule) {
            $sessions = $schedule->sessions;

            $total_session_count = count($sessions);

            if ($total_session_count > 0){
                $completed = count($sessions->where('status', 'completed'));
                $progress = round((($completed/$total_session_count) * 100), 2);

                $missed = count($sessions->where('status', 'failed'));
                $left = count($sessions->where('status', 'incomplete'));
            } else {
                $completed = $progress = $missed = $left = 0;
            }
        }

        return response()->json(
            [
                'name' => $user->name,
                'progress' => $progress,
                'completed' => $completed,
                'missed' => $missed,
                'left' => $left
            ]
        );
    }


    /**
     * Session Function
     * 
     * This function is used by the Android App 
     * via the API to retrieve users session information
     *
     * @param Request $request Request object received via GET/POST
     * 
     * @return Response
     */
    public function sessions(Request $request, $count)
    {
        $user = $request->user();

        if ($schedule = $user->schedule) {
            $sessions = $schedule->sessions()->get();
        
            if (!empty($sessions)) {
                $module_list = array();
                $today = Carbon::now()->addDays($count)->startOfDay();

                foreach ($sessions as $session) {
                    $date = new Carbon($session->date);
                    if ($date->equalTo($today)) {
                        array_push(
                            $module_list, array(
                                'id' => $session->id, 
                                'module' => $session->module, 
                                'status' => $session->status
                            )
                        );
                    }
                }
            }
        }

        if (!(isset($module_list)) || empty($module_list)) {
            $module_list = array();
            array_push(
                $module_list, array(
                    'id' => 0, 
                    'module' => null, 
                    'status' => null
                )
            );
        }
        
        return response()->json(
            [
                'name' => $user->name,
                'date' => $today->toDateString(),
                'sessions' => $module_list
            ]
        );
    }


    /**
     * Event Function
     * 
     * This function is used by the Android App 
     * via the API to retrieve users event information
     *
     * @param Request $request Request object received via GET/POST
     * 
     * @return Response
     */
    public function events(Request $request)
    {
        $user = $request->user();

        if ($schedule = $user->schedule) {
            $events = $schedule->events()->get();
        
            if (!empty($events)) {
                $event_list = array();

                foreach ($events as $event) {
                    $date = new Carbon($event->date);
                    if ($date->isCurrentMonth()) {
                        array_push(
                            $event_list, array(
                                'event' => $event->description,
                                'date' => $event->date
                            )
                        );
                    }
                }
            }
        }

        if (!(isset($event_list)) || empty($event_list)) {
            $event_list = array();
            array_push(
                $event_list, array(
                    'event' => null, 
                    'date' => null
                )
            );
        }
        
        return response()->json(
            [
                'name' => $user->name,
                'events' => $event_list
            ]
        );
    }



    /**
     * Get Sessions Function
     * 
     * This is used by the Android App
     * via API to get the details of a
     * Session
     *
     * @param int $id Session ID
     * 
     * @return Response
     */
    public function getSession($id)
    {
        $session = Session::find($id);
        return response()->json(
            [
                'id' => $id,
                'module' => $session->module,
                'status' => $session->status
            ]
        );
    }

    /**
     * Session Complete Function
     * 
     * This marks a session as complete,
     * once it is called by the Android App
     *
     * @param int $id Session ID
     * 
     * @return Response
     */
    public function sessionComplete($id)
    {
        $session = Session::find($id);
        $session->status = "completed";
        $session->completed_time = Carbon::now();
        $session->save();

        return response()->json(
            [
                'message' => 'Completed Session'
            ]
        );
    }

    /**
     * Session Check Function
     * 
     * This checks if there are any sessions
     * for the user to study today
     *
     * @param Request $request Request object received via GET/POST
     * 
     * @return Response
     */
    public function sessionCheck(Request $request)
    {
        $user = $request->user();
        $count = 0;

        if ($schedule = $user->schedule) {
            $sessions = $user->schedule->sessions()->get();

            if (!empty($sessions)) {
                foreach ($sessions as $session) {
                    $date = new Carbon($session->date);
                    if ($date->isToday() && !($session->status == 'completed')) {
                        $count++;
                    }
                }
            }
        }

        if ($count > 0) {
            return response()->json(
                [
                    'toStudy' => true
                ]
            );
        } else {
            return response()->json(
                [
                    'toStudy' => false
                ]
            );
        }
    }
}
