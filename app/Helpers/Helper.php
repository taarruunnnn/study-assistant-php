<?php

if (!function_exists('study_scheduler'))
{
    function study_scheduler($request)
    {
        $start = strtotime($request->input('start'));
        $end = strtotime($request->input('end'));

        $noDays = round((($end - $start)/(60 * 60 * 24)));

        $noModules = count($request->input('module'));

        $daysPerModule = intval($noDays/$noModules);

        $evenDays = $daysPerModule * $noModules;

        $unevenDays = $noDays - $evenDays;

        $s = 'Days: '.$noDays.' | Rest Days: '.$unevenDays.' | Modules: '.$noModules.' | Days Per Module: '.$daysPerModule;

        $sched = array();
        $x = 0;

        $sched[] = $s;

        for ($i=0; $i < $noDays; $i++) 
        { 

            if ($x < ($noModules-1) && $i < $evenDays) {
                $sched[] = 'day: '.$i.' | module: '.$x;
                $x++;
            } elseif (!($x < ($noModules-1)) && $i < $evenDays) {
                $sched[] = 'day: '.$i.' | module: '.$x;
                $x = 0;
            } else {
                $sched[] = 'uneven day '.$i;
            }
        }

        return ($sched);
    }
}