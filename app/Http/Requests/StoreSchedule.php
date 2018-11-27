<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSchedule extends FormRequest
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
            'start' => 'required|date',
            'end' => 'required|date',
        ];
    }

    public function persist()
    {
        $user = Auth::user();

        if ($user->schedule === null) {
            $strStart = request('start');        

            $start = strtotime(request('start'));
            $end = strtotime(request('end'));

            $noDays = round((($end - $start)/(60 * 60 * 24)));

            $noModules = count(request('module'));

            $daysPerModule = intval($noDays/$noModules);

            $evenDays = $daysPerModule * $noModules;

            $revision = date('Y-m-d', strtotime($strStart. ' + '.$evenDays.' days'));

            $schedule = $user-> schedule()-> create([
                'start' => request('start'),
                'revision' => $revision,
                'end' => request('end'),
            ]);


            foreach (request('module') as  $key => $value){
                $x = $key - 1;
                $schedule-> modules()-> create([
                    'name' => $value,
                    'rating' => $this-> rating[$key],
                    'start' => date('Y-m-d', strtotime($strStart. ' + '.$x.' days')),
                    'rep' => $noModules
                ]);
            }

            return true;
        }
        else 
        {
            return false;
        }
    }
}
