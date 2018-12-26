<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

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

    // public function persist()
    // {
    //     $user = Auth::user();
    //     $request = $this;

    //     if ($user->schedule === null) 
    //     {

    //         $weekday_hours = request('weekdays');
    //         $weekend_hours = request('weekends');

    //         $schedule = $user-> schedule()-> create([
    //             'start' => request('start'),
    //             'end' => request('end'),
    //             'weekday_hours' => request('weekdays'),
    //             'weekend_hours' => request('weekends')
    //         ]);

    //         $start_date = new Carbon(request('start'));
    //         $end_date = new Carbon(request('end'));

    //         $no_week_days = $start_date->diffInWeekdays($end_date);
    //         $no_weekend_days = $start_date->diffInWeekendDays($end_date);
    //         $no_days = $start_date->diffInDays($end_date);

    //         $total_study_hours = ($no_week_days * $weekday_hours) + ($no_weekend_days * $weekend_hours);

    //         $total_rating = 0;

    //         foreach (request('rating') as $rating) 
    //         {
    //             $total_rating += $rating;
    //         }

    //         $sessions = array(); //a multidimensional array
            
    //         foreach(request('module') as $key => $value)
    //         {
    //             $hours_per_module = intval($total_study_hours * (($request->rating[$key])/$total_rating));
                
    //             $sessions[$key]['module'] = $value;
    //             $sessions[$key]['rating'] = $request->rating[$key];
    //             $sessions[$key]['hours'] = $hours_per_module;

    //             $schedule-> modules() -> create
    //             ([
    //                 'name' => $value,
    //                 'rating' => $request->rating[$key]
    //             ]);
    //         }

    //         // Sorting the array based on hours
    //         usort($sessions, function($a, $b){
    //             return $a['hours'] <=> $b['hours'];
    //         });

    //         for ($i=0; $i < $no_days; $i++) 
    //         {
    //             $today = $start_date->copy()->addDays($i);

    //             if ($today->isWeekday()) 
    //             {
    //                 for ($x=0; $x < $weekday_hours; $x+=2) 
    //                 { 
    //                     if(!(empty($sessions)))
    //                     {   
    //                         $rand = array_rand($sessions, 1);

    //                         $schedule -> sessions() -> create
    //                         ([
    //                             'module' => $sessions[$rand]['module'],
    //                             'date' => $today->toDateString()
    //                         ]);

    //                         $sessions[$rand]['hours'] =  $sessions[$rand]['hours'] - 2;
                            
    //                         if ($sessions[$rand]['hours'] <= 0) 
    //                         {
    //                             unset($sessions[$rand]);
    //                         }
                        
    //                     }
                        
    //                 }
    //             }
    //             elseif ($today->isWeekend()) 
    //             {
                    

    //                 for ($x=0; $x < $weekend_hours; $x+=2) 
    //                 {   
    //                     if(!(empty($sessions)))
    //                     {
    //                         $rand = array_rand($sessions, 1);

    //                         $schedule -> sessions() -> create
    //                         ([
    //                             'module' => $sessions[$rand]['module'],
    //                             'date' => $today->toDateString()
    //                         ]);

    //                         $sessions[$rand]['hours'] =  $sessions[$rand]['hours'] - 2;

    //                         if ($sessions[$rand]['hours'] <= 0) 
    //                         {
    //                             unset($sessions[$rand]);
    //                         }
                            
    //                     }
    //                 }
    //             }

    //             // Adds to sessions count
    //             $month = $today->englishMonth;
    //             $session_counts = $schedule->session_counts()->where('month', $month)->first();

    //             if ($session_counts === null)
    //             {
    //                 $schedule->session_counts()->create
    //                 ([
    //                     'month' => $month,
    //                     'count' => 1
    //                 ]);
    //             }
    //             else
    //             {
    //                 $count = $session_counts->count;
    //                 $count++;
    //                 $session_counts->count = $count;
    //                 $session_counts->save();
    //             }
                
    //         }
    //     }
    // }
}
