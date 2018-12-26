<?php

use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Carbon;

if (!function_exists('schedule_retriever'))
{
    function schedule_retriever()
    {
        $user = Auth::user();
        if ($schedule = $user->schedule)
        {
            $data = [];
            $sessions = $schedule->sessions;
            
            $scheduleStart = $schedule->start;
            $scheduleEnd= $schedule->end;

            $colors = array("#00bcd4", "#2b8cba", "#3f51b5", "#9c27b0", "#f442c8", "#e65100", "#8bc34a", "#4caf50", "#797979", "#2196f3");
            $x = 0;
            $date;
            $daysForRevision = array();
            $modules = array();

            foreach ($sessions as $session) 
            {
                if(!in_array($session->module, $modules, true))
                {
                    array_push($modules, $session->module);
                }

                $colorKey = array_search($session->module, $modules);

                $color = $colors[$colorKey];
                $x++;
                    
                if($session['status'] == "incomplete")
                {
                    $data[]= 
                    [
                        'id' => $session->id,
                        'title' => $session->module,
                        'start' => $session->date,
                        'end' => $session->date,
                        'color' => $color
                    ];
                }
                elseif ($session['status'] == "failed") 
                {
                    $data[]= 
                    [
                        'id' => $session->id,
                        'title' => $session->module,
                        'start' => $session->date,
                        'end' => $session->date,
                        'color' => '#ec3737'
                    ];
                }
                elseif ($session['status'] == "completed") 
                {
                    $data[]= 
                    [
                        'id' => $session->id,
                        'title' => $session->module,
                        'start' => $session->date,
                        'end' => $session->date,
                        'color' => '#38c172'
                    ];
                }
                     
    
            }

            return $data;
        }

    }
    
}

