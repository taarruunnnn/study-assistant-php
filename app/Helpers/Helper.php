<?php

use Illuminate\Support\Facades\Auth;

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

            $colors = array("#00bcd4", "#03a9f4", "#607d8b", "#3f51b5", "#9c27b0", "#e91e63", "#e65100", "#8bc34a", "#4caf50", "#797979", "#2196f3");
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
                    

                    $data[]= 
                    [
                        'title' => $session->module,
                        'start' => $session->date,
                        'end' => $session->date,
                        'color' => $color
                    ]; 

                    // array_push($daysForRevision, $day);
                
                    
            }

            // $maxDate = max($daysForRevision);
            // $maxDateTime = date('Y-m-d', strtotime($scheduleStart . " +".$maxDate." days"));
            // $s = new DateTime($maxDateTime);
            // $e = new DateTime($scheduleEnd);
            
            // $revisionCount = $e->diff($s);
            // $revisionCount = $revisionCount->format("%a");

            
            // for ($i=1; $i <= $revisionCount; $i++) { 
            //     $dateRev = date('Y-m-d', strtotime($maxDateTime . " +".$i." days"));

            //         $data[]= 
            //         [
            //             'title' => 'Revision',
            //             'start' => $dateRev,
            //             'end' => $dateRev,
            //             'color' => '#000'
            //         ];
            // }

            return $data;
            }

        
    }
}