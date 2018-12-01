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
            $noModules = count(request('module'));

            $evenDays = intval($noDays/$noModules) * $noModules;

            $totalRating = 0;
            foreach (request('rating') as  $key => $value)
            {
                $totalRating += $value;
            }

            
            $daysPerModuleArray = array();
            foreach (request('module') as  $key => $value)
            {
                $rating = $request-> rating[$key];
                $percentage = $rating/$totalRating;
                $daysPerModule = intval($noDays * $percentage);
                array_push($daysPerModuleArray, $daysPerModule);

            }

            $minDays = min($daysPerModuleArray);

            foreach (request('module') as  $key => $value)
            {
                $rating = $request-> rating[$key];
                $percentage = $rating/$totalRating;
                $daysPerModule = intval($noDays * $percentage);
                
                $scheduledDays = array();
                $i = $key;

                while ($i <= $minDays) 
                {
                    array_push($scheduledDays, $i);
                    $i += $noModules;
                }

                // for ($x=0; $x < $noModules; $x++) 
                // { 
                    

                    // if (($key = array_search($minDays, $daysPerModuleArray)) !== false) 
                    // {
                    //     unset($daysPerModuleArray[$key]);
                    // }

                    // if(!(empty($daysPerModuleArray)))
                    // {
                    //     $minDays = min($daysPerModuleArray);
                    // }
                // }
                

                $schedule-> modules()-> create([
                    'name' => $value,
                    'rating' => $rating,
                    'days' => $scheduledDays
                ]);

                
            }

            return $daysPerModuleArray;
        }
        else 
        {
            return false;
        }
    }
}

// V2

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
            $noModules = count(request('module'));

            $evenDays = intval($noDays/$noModules) * $noModules;

            $totalRating = 0;
            foreach (request('rating') as  $key => $value)
            {
                $totalRating += $value;
            }

            
            $daysPerModuleArray = array();
            foreach (request('module') as  $key => $value)
            {
                $rating = $request-> rating[$key];
                $percentage = $rating/$totalRating;
                $daysPerModule = intval($noDays * $percentage);
                array_push($daysPerModuleArray, $daysPerModule);

            }

            $minDays = min($daysPerModuleArray);
            $i = 1;
            
            foreach (request('module') as  $key => $value)
            {
                $rating = $request-> rating[$key];
                $percentage = $rating/$totalRating;
                $daysPerModule = intval($noDays * $percentage);
                
                $scheduledDays = array();
                $c = $i;

                while ($c <= $minDays) 
                {
                    array_push($scheduledDays, $c);
                    $c += $noModules;
                }

                

                    if (($key = array_search($minDays, $daysPerModuleArray)) !== false) 
                    {
                        unset($daysPerModuleArray[$key]);
                    }

                    if(!(empty($daysPerModuleArray)))
                    {
                        $minDays = min($daysPerModuleArray);
                    }
                
                

                $schedule-> modules()-> create([
                    'name' => $value,
                    'rating' => $rating,
                    'days' => $scheduledDays
                ]);

                $i++;
                $noModules--;
            }

            return $minDays;
        }
        else 
        {
            return false;
        }
    }
}

// V3



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
            $noModules = count(request('module'));

            $evenDays = intval($noDays/$noModules) * $noModules;

            $totalRating = 0;
            foreach (request('rating') as  $key => $value)
            {
                $totalRating += $value;
            }

            
            $daysPerModuleArray = array();
            foreach (request('module') as  $key => $value)
            {
                $rating = $request-> rating[$key];
                $percentage = $rating/$totalRating;
                $daysPerModule = intval($noDays * $percentage);
                array_push($daysPerModuleArray, $daysPerModule);

            }
            $minDays = min($daysPerModuleArray);

            
            $moduleRatings = array();
            foreach (request('rating') as $key => $value) 
            {
                array_push($moduleRatings, $value);
            }


            
            $i = 1;
            
            for ($c=0; $c < $noModules; $c++) 
            { 
                $minModule = min($moduleRatings);
                $minKey = array_search($minModule, $moduleRatings);

                foreach ($moduleRatings as $key => $value) 
                {
                    $rating = $request-> rating[$key + 1];

                    $scheduledDays = array();
                    $x = $i;

                    while ($x <= $minDays) 
                    {
                        array_push($scheduledDays, $x);
                        $x += $noModules;
                    }

                    $schedule-> modules()-> create([
                        'name' => $request->module[$key + 1],
                        'rating' => $rating,
                        'days' => $scheduledDays
                    ]);

                    $i++;
                }
            }

            return $minKey  ;
        }
        else 
        {
            return false;
        }
    }
}

