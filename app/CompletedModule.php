<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CompletedModule extends Model
{
    protected $fillable = [
        'name', 'rating', 'grade', 'completed_sessions', 'failed_sessions'
    ];

    public function user()
    {
        return $this->belongsTo('App\CompletedModules');
    }

    public function archive()
    {
        $user = Auth::user();
        $schedule = $user->schedule;


        $modules = $schedule->modules;

        foreach($modules as $module)
        {
            $sessions = $schedule->sessions;
            $completedCount = 0;
            $failedCount = 0;
            foreach($sessions as $session)
            {
                if($session->module == $module->name)
                {
                    if($session->status == 'completed')
                    {
                        $completedCount++;
                    }
                    elseif($session->status == 'failed' || $session->status == 'incomplete')
                    {
                        $failedCount++;
                    }
                }
            }

            $completed_module = $user->completed_modules()->create
            ([
                'name' => $module->name,
                'rating' => $module->rating,
                'completed_sessions' => $completedCount,
                'failed_sessions' => $failedCount
            ]);

            $module->delete();
        }
        
        $schedule->delete();

    }
}
