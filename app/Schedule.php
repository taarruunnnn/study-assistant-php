<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class Schedule extends Model
{
    /**
     * Mass assignable variables
     *
     * @var array
     */
    protected $fillable = [
        'start', 'end', 'weekday_hours', 'weekend_hours'
    ];

    
    /**
     * User relationship
     *
     * @return Relationship
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Sessions relationship
     *
     * @return Relationship
     */
    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    /**
     * Reports relationship
     *
     * @return Relationship
     */
    public function reports()
    {
        return $this->hasMany('App\Report');
    }

    /**
     * Modules relationship
     *
     * @return Relationship
     */
    public function modules()
    {
        return $this->hasMany('App\Module');
    }

    /**
     * Events relationship
     *
     * @return Relationship
     */
    public function events()
    {
        return $this->hasMany('App\Event');
    }

   
    public $user;
    public $request;

    /**
     * Create Schedule Function
     * 
     * Takes in user and request variables and generates
     * a schedule for the user
     *
     * @param User    $user    User object
     * @param Request $request Request object
     * 
     * @return void
     */
    public function createSchedule($user, $request)
    {
        if ($user->schedule === null) {
            $this->user = $user;
            $this->request = $request;

            $weekday_hours = $request->weekdays;
            $weekend_hours = $request->weekends;
            $start = $request->start;
            $end = $request->end;

            $schedule = $user-> schedule()-> create(
                [
                    'start' => $start,
                    'end' => $end,
                    'weekday_hours' => $weekday_hours,
                    'weekend_hours' => $weekend_hours
                ]
            );

            $total_study_hours = $this->totalStudyHourCalculator();
            $total_rating = $this->totalRatingCalculator();
            $modules = $this->moduleCreator($total_study_hours, $total_rating, $schedule);
            $this->sessionCreator($modules, $schedule);
        }
    }

    /**
     * Total Study Hour Calculator Function
     * 
     * Calculates the number of study hours between
     * the start date and end date of the schedule
     *
     * @return int
     */
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

    /**
     * Total Rating Calculator Function
     * 
     * Calculates the sum of student ratings
     *
     * @return int
     */
    public function totalRatingCalculator()
    {
        $total_rating = 0;

        foreach ($this->request->rating as $rating) {
            $total_rating += $rating;
        }

        return $total_rating;
    }

    /**
     * Module Creator Function
     *
     * @param int      $total_study_hours Amount of total hours to study
     * @param int      $total_rating      Total rating of modules
     * @param Schedule $schedule          Schedule
     * 
     * @return array
     */
    public function moduleCreator($total_study_hours, $total_rating, $schedule)
    {
        $modules = array(); //a multidimensional array
            
        foreach ($this->request->module as $key => $value) {
            $average_rating = (($this->request->rating[$key])/$total_rating);
            $hours_per_module = intval($total_study_hours * $average_rating);
            
            $modules[$key]['module'] = $value;
            $modules[$key]['rating'] = $this->request->rating[$key];
            $modules[$key]['hours'] = $hours_per_module;

            $schedule-> modules() -> create(
                [
                    'name' => $value,
                    'rating' => $this->request->rating[$key]
                ]
            );
        }

        return $modules;
    }

    /**
     * Session Creator Function
     * 
     * Creates sessions that would then be saved
     * to the database
     *
     * @param array    $modules  Modules array
     * @param Schedule $schedule Schedule object
     * 
     * @return void
     */
    public function sessionCreator($modules, $schedule)
    {
        $weekday_hours = $this->request->weekdays;
        $weekend_hours = $this->request->weekends;
        $start = $this->request->start;
        $end = $this->request->end;

        $start_date = new Carbon($start);
        $end_date = new Carbon($end);
        $no_days = $start_date->diffInDays($end_date);

        /**
         * Loops through all the days of the schedule.
         * Checks if day is weekday or weekend and allocates
         * hours according to users study availability
         */
        for ($i=0; $i < $no_days; $i++) {
            $today = $start_date->copy()->addDays($i);

            if ($today->isWeekday()) {
                for ($x=0; $x < $weekday_hours; $x+=2) {
                    if (!(empty($modules))) {
                        $rand = array_rand($modules, 1);

                        $session = $schedule -> sessions() -> create(
                            [
                                'module' => $modules[$rand]['module'],
                                'date' => $today->toDateString()
                            ]
                        );

                        /**
                         * Once a session has been written to the database,
                         * two hours of its total study time is removed
                         */
                        $modules[$rand]['hours'] =  $modules[$rand]['hours'] - 2;
                        
                        if ($modules[$rand]['hours'] <= 0) {
                            unset($modules[$rand]);
                        }

                        /**
                         * TEST DATA
                         * 
                         * This is added to populate the database with dummy data
                         * in order to facilitate the testing process
                         */
                        if ($this->request->test === '1') {
                            $rand_hour = rand(1, 23);
                            $td = $today->copy()->addHours($rand_hour);
                            $completed = $td->toDateTimeString();
                            $session->completed_time = $completed;
                            $session->status = "completed";
                            $session->save();
                        }
                    }
                }
            } elseif ($today->isWeekend()) {
                for ($x=0; $x < $weekend_hours; $x+=2) {
                    if (!(empty($modules))) {
                        $rand = array_rand($modules, 1);

                        $schedule -> sessions() -> create(
                            [
                                'module' => $modules[$rand]['module'],
                                'date' => $today->toDateString()
                            ]
                        );

                        $modules[$rand]['hours'] =  $modules[$rand]['hours'] - 2;

                        if ($modules[$rand]['hours'] <= 0) {
                            unset($modules[$rand]);
                        }
                    }
                }
            }
        }
    }
}
