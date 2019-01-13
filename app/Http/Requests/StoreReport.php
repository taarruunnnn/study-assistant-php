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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function persist()
    {
        if ($schedule = Auth::user()->schedule) {
            $modules = $schedule->modules;
            $sessions = $schedule->sessions;

            $total_session_count = count($sessions);
            $completed_session_count = count($sessions->where('status', 'completed'));
            $progress = round((($completed_session_count/$total_session_count) * 100), 2);

            $no_modules = count($modules);
            $sessions_completed = $sessions->where('status', 'completed')->count();
            $sessions_missed = $sessions->where('status', 'failed')->count();
            $sessions_incomplete = $sessions->where('status', 'incomplete')->count();


            $schedule->reports()->create([
                'no_modules' => $no_modules,
                'sessions_completed' => $sessions_completed,
                'sessions_missed' => $sessions_missed,
                'sessions_incomplete' => $sessions_incomplete,
                'progress' => $progress,
                'sessions' => request('sessions'),
                'time_spent' => request('comparedtime'),
                'study_times' => request('studytimes')
            ]);
        }
    }
}
