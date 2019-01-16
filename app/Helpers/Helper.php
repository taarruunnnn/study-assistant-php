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
            
            $scheduleStart = $schedule->start;
            $scheduleEnd= $schedule->end;

            $colors = array(
                        "#00bcd4", 
                        "#2b8cba", 
                        "#3f51b5", 
                        "#9c27b0", 
                        "#f442c8", 
                        "#e65100", 
                        "#8bc34a", 
                        "#4caf50", 
                        "#797979", 
                        "#2196f3"
                    );
                    
            $x = 0;
            $date;
            $daysForRevision = array();
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
                                'color' => '#ec3737',
                                'description' => 'session'
                            ];
                } elseif ($session['status'] == "completed") {
                    $data[] = [
                                'id' => $session->id,
                                'title' => $session->module,
                                'start' => $session->date,
                                'end' => $session->date,
                                'color' => '#38c172',
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
                                'color' => '#bd4747',
                                'description' => 'event',
                                'className' => 'calendarEvent'
                            ];
                }
            }
            return $data;
        }
    }
}
