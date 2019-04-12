<?php
/**
 * This file contains custom helper functions 
 * which are autoloaded using composer.json
 * They are available globally.
 */
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Carbon;

if (!function_exists('scheduleRetriever')) {
    /**
     * Schedule Retriever Function
     * 
     * This function retrieves the session data from the database,
     * processes it and returns it in an array.
     *
     * @return array
     */
    function scheduleRetriever($user)
    {
        if ($schedule = $user->schedule) {
            $data = [];
            $sessions = $schedule->sessions;

            $colors = array(
                        "#9E9E9E", 
                        "#0097A7", 
                        "#4DB6AC", 
                        "#607D8B", 
                        "#1E88E5", 
                        "#BA68C8", 
                        "#673AB7", 
                        "#00BCD4", 
                        "#3F51B5", 
                        "#03A9F4"
                    );
                    
            $x = 0;
            $modules = array();

            foreach ($sessions as $session) {
                if (!in_array($session->module, $modules, true)) {
                    array_push($modules, $session->module);
                }

                $colorKey = array_search($session->module, $modules);

                $color = $colors[$colorKey];
                $x++;
                    
                if ($session['status'] == "incomplete") {
                    $data[] = [
                                'id' => $session->id,
                                'title' => $session->module,
                                'start' => $session->date,
                                'end' => $session->date,
                                'color' => $color,
                                'description' => 'session'
                            ];
                } elseif ($session['status'] == "failed") {
                    $data[] = [
                                'id' => $session->id,
                                'title' => $session->module,
                                'start' => $session->date,
                                'end' => $session->date,
                                'color' => '#D7302A',
                                'description' => 'session'
                            ];
                } elseif ($session['status'] == "completed") {
                    $data[] = [
                                'id' => $session->id,
                                'title' => $session->module,
                                'start' => $session->date,
                                'end' => $session->date,
                                'color' => '#038103',
                                'description' => 'session'
                            ];
                }
            }

            if ($events = $schedule->events) {
                foreach ($events as $event) {
                    $data[] = [
                                'id' => $event->id,
                                'title' => $event->description,
                                'start' => $event->date,
                                'end' => $event->date,
                                'color' => '#562424',
                                'description' => 'event',
                                'className' => 'calendarEvent'
                            ];
                }
            }
            return $data;
        }
    }
}

if (!(function_exists('failedSessionMarker'))){
    function failedSessionMarker($user){
        if ($schedule = $user->schedule) {
            $sessions = $schedule->sessions;
            $today = Carbon::today();
            foreach ($sessions as $session) {
                $date = new Carbon($session->date);
                $status = $session->status;
                if ($date->lessThan($today) && $status == "incomplete") {
                    $session->status = "failed";
                    $session->save();
                }
            }
        }
    }
}
