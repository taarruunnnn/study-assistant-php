<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class UpdateSchedule extends FormRequest
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
        $request = $this;

        $schedule = $user->schedule;

        $schedule->start = request('start');
        $schedule->end = request('end');
        $schedule->weekday_hours = request('weekdays');
        $schedule->weekend_hours = request('weekends');

        $schedule->save();

        $weekday_hours = request('weekdays');
        $weekend_hours = request('weekends');

        $start_date = new Carbon(request('start'));
        $end_date = new Carbon(request('end'));
        $modified_date = Carbon::today();

        //find count of sessions passed
        $sessions =  $schedule->sessions;
        $modules = $schedule->modules;

        $module_list = [];
        foreach ($modules as $module) 
        {
            $module_list[] = [
                'name' => $module['name'],
                'count' => 0
            ];

        }

        $schedule->modules()->delete();

        foreach ($sessions as $session) 
        {
            foreach ($module_list as $key => $module_list_item) 
            {
                if($session['module'] == $module_list_item['name'] && $session['status'] == "failed")
                {
                    $module_list[$key]['count']++;
                }
            }

            $session_date = new Carbon($session['date']);

            if($session_date->greaterThanOrEqualTo($modified_date))
            {
                $session->delete();
            }
        }

        $no_week_days = $start_date->diffInWeekdays($end_date);
        $no_weekend_days = $start_date->diffInWeekendDays($end_date);
        $no_days = $start_date->diffInDays($end_date);
        $no_days_modified = $modified_date->diffInDays($end_date);

        $total_study_hours = ($no_week_days * $weekday_hours) + ($no_weekend_days * $weekend_hours);

        
        $total_rating = 0;
        foreach (request('rating') as $rating) 
        {
            $total_rating += $rating;
        }

        $sessions = array(); //a multidimensional array
        
        foreach(request('module') as $key => $value)
        {
            $hours_per_module = intval($total_study_hours * (($request->rating[$key])/$total_rating));

            foreach ($module_list as $module_list_item) 
            {
                if($module_list_item['name'] == $value)
                {
                    $hours_per_module -= ($module_list_item['count'] * 2);
                }
            }
            
            $sessions[$key]['module'] = $value;
            $sessions[$key]['rating'] = $request->rating[$key];
            $sessions[$key]['hours'] = $hours_per_module;

            $module = $schedule->modules()->firstOrCreate([
                'name' => $value,
                'rating' => $request->rating[$key]
            ]);
            
        }
        

        // Sorting the array based on hours
        usort($sessions, function($a, $b){
            return $a['hours'] <=> $b['hours'];
        });

        
        for ($i=0; $i < $no_days_modified; $i++) 
        {
            $looping_day = $modified_date->copy()->addDays($i);

            if ($looping_day->isWeekday()) 
            {
                for ($x=0; $x < $weekday_hours; $x+=2) 
                { 
                    if(!(empty($sessions)))
                    {   
                        $rand = array_rand($sessions, 1);

                        $schedule -> sessions() -> create
                        ([
                            'module' => $sessions[$rand]['module'],
                            'date' => $looping_day->toDateString()
                        ]);

                        $sessions[$rand]['hours'] =  $sessions[$rand]['hours'] - 2;
                        
                        if ($sessions[$rand]['hours'] <= 0) 
                        {
                            unset($sessions[$rand]);
                        }
                    
                    }
                    
                }
            }
            elseif ($looping_day->isWeekend()) 
            {
                
                for ($x=0; $x < $weekend_hours; $x+=2) 
                {   
                    if(!(empty($sessions)))
                    {
                        $rand = array_rand($sessions, 1);

                        $schedule -> sessions() -> create
                        ([
                            'module' => $sessions[$rand]['module'],
                            'date' => $looping_day->toDateString()
                        ]);

                        $sessions[$rand]['hours'] =  $sessions[$rand]['hours'] - 2;

                        if ($sessions[$rand]['hours'] <= 0) 
                        {
                            unset($sessions[$rand]);
                        }
                        
                    }
                }
            }
        }

        return $sessions;

    }
}
