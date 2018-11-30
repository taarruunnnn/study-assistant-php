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
        $request = $this;

        if ($user->schedule === null) 
        {

            $schedule = $user-> schedule()-> create([
                'start' => request('start'),
                'end' => request('end'),
            ]);

            $start = strtotime(request('start'));
            $end = strtotime(request('end'));

            $noDays = round((($end - $start)/(60 * 60 * 24)));

            $totalRating = 0;
            $moduleRatings = array();
            foreach (request('rating') as  $key => $value)
            {
                $totalRating += $value;
            }

            
            $daysPerModuleArray = array();
            $modules = array();
            foreach (request('module') as  $key => $value)
            {
                $rating = $request-> rating[$key];
                $percentage = $rating/$totalRating;
                $daysPerModule = intval($noDays * $percentage);
                array_push($daysPerModuleArray, $daysPerModule);

                $modules[$value] = $request-> rating[$key];    //module name : rating
            }
            asort($modules);
            $noModules = count($modules);

            sort($daysPerModuleArray);
            $minDays = min($daysPerModuleArray);
            

            $prevMin = array();
            array_push($prevMin, $minDays);

            $noModulesx = $noModules;
            $i = 1;

            foreach ($modules as  $key => $value)
            {
                $scheduledDays = array();

                $temp = $i;

                $noModules = $noModulesx;
                $keyMin = 0;
                
                for ($k=0; $k < $minDays; $k++) 
                { 
                    

                    if (($keyMin = array_search($k, array_reverse($prevMin, true))) !== false)
                    {
                        $noModules = $noModulesx;
                        $noModules -= ($keyMin+1);
                        $temp--;
                    }
                    
                    array_push($scheduledDays, $temp);
                    $temp += $noModules;  
                    
                }

                    if (($keyDay = array_search($minDays, $daysPerModuleArray)) !== false) 
                    {
                        unset($daysPerModuleArray[$keyDay]);
                    }

                    if(!(empty($daysPerModuleArray)))
                    {   
                        
                        $minDays = min($daysPerModuleArray);
                        array_push($prevMin, $minDays);
                    }
                
                

                $schedule-> modules()-> create([
                    'name' => $key,
                    'rating' => $value,
                    'days' => $scheduledDays
                ]);

                $i++;
            }

            return $prevMin;
        }
        else 
        {
            return false;
        }
    }
}
