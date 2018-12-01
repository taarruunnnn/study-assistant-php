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
            $modules = $schedule->modules;
            
            $scheduleStart = $schedule->start;
            $scheduleEnd= $schedule->end;

            $colors = array("#00bcd4", "#03a9f4", "#2196f3", "#3f51b5", "#9c27b0", "#e91e63", "#e65100", "#8bc34a", "#4caf50", "#797979", "#607d8b");
            $x = 0;
            $date;

            foreach ($modules as $module) 
            {
                $color = $colors[$x];
                $x++;

                $days = $module->days;
                foreach ($days as $day)
                {
                    $date = date('Y-m-d', strtotime($scheduleStart . " +".$day." days"));

                    $data[]= 
                    [
                        'title' => $module->name,
                        'start' => $date,
                        'end' => $date,
                        'color' => $color
                    ]; 
                }
                    
            }

            $s = new DateTime($date);
            $e = new DateTime($scheduleEnd);
            
            $revisionCount = $e->diff($s);
            $revisionCount = $revisionCount->format("%a");

            
            for ($i=1; $i <= $revisionCount; $i++) { 
                $dateRev = date('Y-m-d', strtotime($date . " +".$i." days"));

                    $data[]= 
                    [
                        'title' => 'Revision',
                        'start' => $dateRev,
                        'end' => $dateRev,
                        'color' => '#000'
                    ];
            }

            return $data;
            }

        
    }
}