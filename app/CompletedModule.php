<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CompletedModule extends Model
{
    protected $fillable = [
        'name', 'rating', 'grade', 'sessions'
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
            $count = 0;
            foreach($sessions as $session)
            {
                if($session->module == $module->name)
                {
                    $count++;
                }
            }

            $completed_module = $user->completed_modules()->create
            ([
                'name' => $module->name,
                'rating' => $module->rating,
                'sessions' => $count
            ]);

            $module->delete();
        }
        
        $schedule->delete();

    }
}
