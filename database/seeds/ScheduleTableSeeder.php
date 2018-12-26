<?php

use Illuminate\Database\Seeder;

class ScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedule = DB::table('schedules')->insert([
            'user_id' => '1',
            'start' => '2018-10-25',
            'end' => '2019-04-20',
            'weekday_hours' => '2',
            'weekend_hours' => '4',
        ]);

        factory(App\Session::class, 50)->create();
    }
}
