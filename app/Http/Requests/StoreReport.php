<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReport extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Rules Function
     * 
     * Checks rules against input.
     * However, no input is used in this instance
     *
     * @return void
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * Persist Function
     * 
     * This function saves a report to the database
     * Instead of obtaining values send via a request from the user,
     * this function generates a report and saves it.
     *
     * @return void
     */
    public function persist()
    {
        if ($schedule = Auth::user()->schedule) {
            $modules = $schedule->modules;
            $sessions = $schedule->sessions;

            $total_session_count = count($sessions);
            $completed_session_count = count(
                $sessions->where(
                    'status', 'completed'
                )
            );
            
            $progress = round(
                (($completed_session_count/$total_session_count) * 100), 2
            );

            $no_modules = count($modules);
            $sessions_completed = $sessions->where('status', 'completed')->count();
            $sessions_missed = $sessions->where('status', 'failed')->count();
            $sessions_incomplete = $sessions->where('status', 'incomplete')->count();


            $schedule->reports()->create(
                [
                    'no_modules' => $no_modules,
                    'sessions_completed' => $sessions_completed,
                    'sessions_missed' => $sessions_missed,
                    'sessions_incomplete' => $sessions_incomplete,
                    'progress' => $progress,
                    'sessions' => request('sessions'),
                    'time_spent' => request('comparedtime'),
                    'study_times' => request('studytimes'),
                    'module_ratings' => request('moduleratings'),
                    'predictions' => request('predictions'),
                    'sessiondetails' => request('sessiondetails')
                ]
            );
        }
    }
}
