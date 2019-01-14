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
     * This function is used by the Android App 
     * via the API to retrieve users schedule information
     *
     * @param Request $request Request object received via GET/POST
     * 
     * @return REsponse
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($schedule = $user->schedule) {
            $sessions = $user->schedule->sessions()->get();
            $events = $schedule->events()->get();

            $module_list = array();
            $event_list = array();
            

            if (!empty($sessions)) {
                foreach ($sessions as $session) {
                    $date = new Carbon($session->date);
                    if ($date->isToday()) {
                        array_push(
                            $module_list, array(
                                'id' => $session->id, 
                                'module' => $session->module, 
                                'status' => $session->status
                            )
                        );
                    }
                }

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
            
                if (empty($module_list)) {
                    array_push(
                        $module_list, array(
                            'id' => 0, 
                            'module' => null, 
                            'status' => null
                        )
                    );
                }

                if (empty($event_list)) {
                    array_push(
                        $event_list, array(
                            'event' => null, 
                            'date' => null
                        )
                    );
                }
            } else {
                $module_list = array();
                array_push(
                    $module_list, array(
                        'id' => 0, 
                        'module' => null, 
                        'status' => null
                    )
                );

                $event_list = array();
                array_push(
                    $event_list, array(
                        'event' => null, 
                        'date' => null
                    )
                );
            }
        } else {
            $module_list = array();
            array_push(
                $module_list, array(
                    'id' => 0, 
                    'module' => null, 
                    'status' => null
                )
            );

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
                'sessions' => $module_list,
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
