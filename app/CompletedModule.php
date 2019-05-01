<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Completed Modules are used to interact with modules 
 * that are part of schedules which were completed.
 */
class CompletedModule extends Model
{
    /**
     * Mass Assignable Variables
     *
     * @var array
     */
    protected $fillable = [
        'name', 'rating', 'grade', 'completed_sessions', 'failed_sessions'
    ];

    /**
     * User Relationship
     *
     * @return Relationship
     */
    public function user()
    {
        return $this->belongsTo('App\CompletedModules');
    }
    
    /**
     * Archive Function
     *
     * This function is used once schedules are completed.
     * Modules will be archived for the purpose of analysis.
     * The number of sessions completed and failed will be
     * calculated and stored along with the module.
     * 
     * @return void
     */
    public function archive()
    {
        $user = Auth::user();
        $schedule = $user->schedule;


        $modules = $schedule->modules;

        foreach ($modules as $module) {
            $sessions = $schedule->sessions;
            $completedCount = 0;
            $failedCount = 0;
            foreach ($sessions as $session) {
                if ($session->module == $module->name) {
                    if ($session->status == 'completed') {
                        $completedCount++;
                    } elseif ($session->status == 'failed' || $session->status == 'incomplete') {
                        $failedCount++;
                    }
                }

                $session->delete();
            }

            $completed_module = $user->completed_modules()->create(
                [
                    'name' => $module->name,
                    'rating' => $module->rating,
                    'completed_sessions' => $completedCount,
                    'failed_sessions' => $failedCount
                ]
            );

            $module->delete();
        }
        
        $schedule->delete();
    }
}
