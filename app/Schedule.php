<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Schedule extends Model
{
    protected $fillable = [
        'start', 'end', 'weekday_hours', 'weekend_hours'
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    public function reports()
    {
        return $this->hasMany('App\Report');
    }

    public function modules()
    {
        return $this->hasMany('App\Module');
    }

    public function session_counts()
    {
        return $this->hasMany('App\SessionCount');
    }

    // Methods
    public $user;
    public $request;

    public function createSchedule($user, $request)
    {
        if ($user->schedule === null)
        {
            $this->user = $user;
            $this->request = $request;

            $weekday_hours = $request->weekdays;
            $weekend_hours = $request->weekends;
            $start = $request->start;
            $end = $request->end;

            $schedule = $user-> schedule()-> create([
                'start' => $start,
                'end' => $end,
                'weekday_hours' => $weekday_hours,
                'weekend_hours' => $weekend_hours
            ]);

            $total_study_hours = $this->totalStudyHourCalculator();
            $total_rating = $this->totalRatingCalculator();
            $modules = $this->moduleCreator($total_study_hours, $total_rating, $schedule);
            $this->sessionCreator($modules, $schedule);
        }
    }

    public function totalStudyHourCalculator()
    {
        $weekday_hours = $this->request->weekdays;
        $weekend_hours = $this->request->weekends;
        $start = $this->request->start;
        $end = $this->request->end;

        $start_date = new Carbon($start);
        $end_date = new Carbon($end);

        $no_week_days = $start_date->diffInWeekdays($end_date);
        $no_weekend_days = $start_date->diffInWeekendDays($end_date);
        $no_days = $start_date->diffInDays($end_date);

        $total_study_hours = ($no_week_days * $weekday_hours) + ($no_weekend_days * $weekend_hours);

        return $total_study_hours;
    }

    public function totalRatingCalculator()
    {
        $total_rating = 0;

        foreach ($this->request->rating as $rating) 
        {
            $total_rating += $rating;
        }

        return $total_rating;
    }

    public function moduleCreator($total_study_hours, $total_rating, $schedule)
    {
        $modules = array(); //a multidimensional array
            
        foreach($this->request->module as $key => $value)
        {
            $hours_per_module = intval($total_study_hours * (($this->request->rating[$key])/$total_rating));
            
            $modules[$key]['module'] = $value;
            $modules[$key]['rating'] = $this->request->rating[$key];
            $modules[$key]['hours'] = $hours_per_module;

            $schedule-> modules() -> create
            ([
                'name' => $value,
                'rating' => $this->request->rating[$key]
            ]);
        }

        return $modules;
    }

    public function sessionCreator($modules, $schedule)
    {
        $weekday_hours = $this->request->weekdays;
        $weekend_hours = $this->request->weekends;
        $start = $this->request->start;
        $end = $this->request->end;

        $start_date = new Carbon($start);
        $end_date = new Carbon($end);
        $no_days = $start_date->diffInDays($end_date);

        // Sorting the array based on hours
        usort($modules, function($a, $b){
            return $a['hours'] <=> $b['hours'];
        });

        for ($i=0; $i < $no_days; $i++) 
        {
            $today = $start_date->copy()->addDays($i);

            if ($today->isWeekday()) 
            {
                for ($x=0; $x < $weekday_hours; $x+=2) 
                { 
                    if(!(empty($modules)))
                    {   
                        $rand = array_rand($modules, 1);

                        $session = $schedule -> sessions() -> create
                        ([
                            'module' => $modules[$rand]['module'],
                            'date' => $today->toDateString()
                        ]);

                        $modules[$rand]['hours'] =  $modules[$rand]['hours'] - 2;
                        
                        if ($modules[$rand]['hours'] <= 0) 
                        {
                            unset($modules[$rand]);
                        }

                        // Adds completed time for populating tests
                        if ($this->request->test === '1')
                        {   
                            $rand_hour = rand(1,23);
                            $td = $today->copy()->addHours($rand_hour);
                            $completed = $td->toDateTimeString();
                            $session->completed_time = $completed;
                            $session->status = "completed";
                            $session->save();
                        }
                    
                        $this->sessionCounter($today, $schedule);

                    }
                    
                }
            }
            elseif ($today->isWeekend()) 
            {
                

                for ($x=0; $x < $weekend_hours; $x+=2) 
                {   
                    if(!(empty($modules)))
                    {
                        $rand = array_rand($modules, 1);

                        $schedule -> sessions() -> create
                        ([
                            'module' => $modules[$rand]['module'],
                            'date' => $today->toDateString()
                        ]);

                        $modules[$rand]['hours'] =  $modules[$rand]['hours'] - 2;

                        if ($modules[$rand]['hours'] <= 0) 
                        {
                            unset($modules[$rand]);
                        }
                        
                        $this->sessionCounter($today, $schedule);

                    }
                    
                }
            }

        }
    }

    public function sessionCounter($today, $schedule)
    {
        $month = $today->englishMonth;
        $session_counts = $schedule->session_counts()->where('month', $month)->first();

        if ($session_counts === null)
        {
            $schedule->session_counts()->create
            ([
                'month' => $month,
                'count' => 1
            ]);
        }
        else
        {
            $count = $session_counts->count;
            $count++;
            $session_counts->count = $count;
            $session_counts->save();
        }
    }
    
}
