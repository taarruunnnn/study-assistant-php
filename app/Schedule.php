<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * The schedule model is used to interact with schedules
 * created by users.
 */
class Schedule extends Model
{
    /**
     * Mass Assignable Variables
     *
     * @var array
     */
    protected $fillable = [
        'start', 'end', 'weekday_hours', 'weekend_hours'
    ];

    
    /**
     * User Relationship
     *
     * @return Relationship
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Sessions Relationship
     *
     * @return Relationship
     */
    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    /**
     * Reports Relationship
     *
     * @return Relationship
     */
    public function reports()
    {
        return $this->hasMany('App\Report');
    }

    /**
     * Modules Relationship
     *
     * @return Relationship
     */
    public function modules()
    {
        return $this->hasMany('App\Module');
    }

    /**
     * Events Relationship
     *
     * @return Relationship
     */
    public function events()
    {
        return $this->hasMany('App\Event');
    }


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
        if ($sched = $user->schedule) {
            $sched->modules()->delete();
            $sched->sessions()->delete();
            $sched->reports()->delete();
            $sched->delete();
        }

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

        $total_study_hours = $this->totalStudyHourCalculator($request);
        $total_rating = $this->totalRatingCalculator($request);
        $modules = $this->moduleCreator($request, $total_study_hours, $total_rating, $schedule);
        $this->sessionCreator($request, $modules, $schedule);

        return $schedule;
        
    }

    /**
     * Total Study Hour Calculator Function
     * 
     * Calculates the number of study hours between
     * the start date and end date of the schedule
     *
     * @param Request $request Request object sent by user
     * 
     * @return int
     */
    public function totalStudyHourCalculator($request)
    {
        $weekday_hours = $request->weekdays;
        $weekend_hours = $request->weekends;
        $start = $request->start;
        $end = $request->end;

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
     * @param Request $request Request object sent by user
     *
     * @return int
     */
    public function totalRatingCalculator($request)
    {
        $total_rating = 0;

        foreach ($request->rating as $rating) {
            $total_rating += (int)$rating;
        }

        return $total_rating;
    }

    /**
     * Module Creator Function
     *
     * @param Request  $request           Request object sent by user
     * @param int      $total_study_hours Amount of total hours to study
     * @param int      $total_rating      Total rating of modules
     * @param Schedule $schedule          Schedule
     * 
     * @return array
     */
    public function moduleCreator($request,$total_study_hours, $total_rating, $schedule)
    {
        $modules = array(); //a multidimensional array
            
        foreach ($request->module as $key => $value) {
            $average_rating = (($request->rating[$key])/$total_rating);
            $hours_per_module = intval($total_study_hours * $average_rating);
            
            $modules[$key]['module'] = $value;
            $modules[$key]['rating'] = $request->rating[$key];
            $modules[$key]['hours'] = $hours_per_module;
            $modules[$key]['weight'] = $average_rating * 100;

            $schedule-> modules() -> create(
                [
                    'name' => $value,
                    'rating' => $request->rating[$key]
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
     * @param Request  $request  Request object sent by user
     * @param array    $modules  Modules array
     * @param Schedule $schedule Schedule object
     * 
     * @return void
     */
    public function sessionCreator($request, $modules, $schedule)
    {
        $weekday_hours = $request->weekdays;
        $weekend_hours = $request->weekends;
        $start = $request->start;
        $end = $request->end;

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
                $prev = array();
                for ($x=0; $x < $weekday_hours; $x+=2) {
                    if (!(empty($modules))) {

                        // Generates a random key based on its weight
                        while(true) {
                            $random = mt_rand(1, 100);
                            foreach ($modules as $key => $module) {
                                $random -= $module['weight'];
                                if ($random <= 0){
                                    $rand = $key;
                                    break;
                                }
                            }

                            if (count($prev) < 2) {
                                array_push($prev, $rand);
                                break;
                            }

                            if ($rand != $prev[count($prev) - 2]){
                                array_push($prev, $rand);
                                break;
                            }

                            if (count($modules) <= 1){
                                break;
                            }
                        }

                        $session = $schedule -> sessions() -> create(
                            [
                                'module' => $modules[$rand]['module'],
                                'date' => $today->toDateString()
                            ]
                        );

                        /**
                         * TEST DATA
                         * 
                         * This is added to populate the database with dummy data
                         * in order to facilitate the testing process
                         */
                        if ($request->test === '1' && $today->lessThan(Carbon::today())) {
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
                $prev = array();
                for ($x=0; $x < $weekend_hours; $x+=2) {
                    if (!(empty($modules))) {
                        
                        // Generates a random key based on its weight
                        while(true) {
                            $random = mt_rand(1, 100);
                            foreach ($modules as $key => $module) {
                                $random -= $module['weight'];
                                if ($random <= 0){
                                    $rand = $key;
                                    break;
                                }
                            }

                            if (count($prev) < 2) {
                                array_push($prev, $rand);
                                break;
                            }

                            if ($rand != $prev[count($prev) - 2]){
                                array_push($prev, $rand);
                                break;
                            }

                            if (count($modules) <= 1){
                                break;
                            }
                        }

                        $schedule -> sessions() -> create(
                            [
                                'module' => $modules[$rand]['module'],
                                'date' => $today->toDateString()
                            ]
                        );
                    }
                }
            }
        }
    }
}
