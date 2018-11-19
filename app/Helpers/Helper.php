<?php

use Illuminate\Support\Facades\Auth;
use RRule\RRule;

if (!function_exists('schedule_retriever'))
{
    function schedule_retriever()
    {
        $user = Auth::user();
        $schedules = $user->schedules()->get();

        $data = [];

        foreach ($schedules as $schedule) {
            $modules = $schedule->modules()->get();

            // Calculations
            
            $scheduleStart = strtotime($schedule->start);
            $scheduleEnd = strtotime($schedule->revision);

            $noDays = round((($scheduleEnd - $scheduleStart)/(60 * 60 * 24)));
            $noModules = count($modules);

            $daysPerModule = intval($noDays/$noModules);


            foreach ($modules as $module) {

                // Use of php-rrule library

                $rrule = new RRule([
                    'FREQ' => 'DAILY',
                    'INTERVAL' => $noModules,
                    'DTSTART' => $module->start,
                    'COUNT' => $daysPerModule
                ]);
                     
                foreach ($rrule as $date)
                {
                    $moduleStart = $date->format('Y-m-d');

                    $data[]= 
                    [
                        'title' => $module->name,
                        'start' => $moduleStart,
                        'end' => $moduleStart,
                        'color' => '#2196f3'
                    ];  
                }
            }

            $rrule2 = new RRule([
                'FREQ' => 'DAILY',
                'DTSTART' => $schedule->revision,
                'UNTIL' => $schedule->end,
            ]);

            foreach ($rrule2 as $date)
                {
                    $moduleStart = $date->format('Y-m-d');

                    $data[]= 
                    [
                        'title' => 'Revision',
                        'start' => $moduleStart,
                        'end' => $moduleStart,
                        'color' => '#000'
                    ];  
                }
        }

        return $data;
    }
}