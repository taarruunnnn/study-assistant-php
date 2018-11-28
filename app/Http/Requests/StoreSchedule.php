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

        if ($user->schedule === null) 
        {

            $schedule = $user-> schedule()-> create([
                'start' => request('start'),
                'end' => request('end'),
            ]);

            $start = strtotime(request('start'));
            $end = strtotime(request('end'));

            $noDays = round((($end - $start)/(60 * 60 * 24)));
            $noModules = count(request('module'));

            $evenDays = intval($noDays/$noModules) * $noModules;

            $totalRating = 0;
            foreach (request('rating') as  $key => $value){
                $totalRating += $value;
            }

            foreach (request('module') as  $key => $value)
            {
                $rating = $this-> rating[$key];
                $percentage = $rating/$totalRating;
                $daysPerModule = intval($noDays * $percentage);
                
                $scheduledDays = array();

                for ($i = $key; $i <= $evenDays; $i += $noModules) 
                { 
                    array_push($scheduledDays, $i);
                }

                $schedule-> modules()-> create([
                    'name' => $value,
                    'rating' => $rating,
                    'days' => $scheduledDays
                ]);
            }

            return $days;
        }
        else 
        {
            return false;
        }
    }
}
