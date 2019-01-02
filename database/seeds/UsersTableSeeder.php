<?php

use Illuminate\Database\Seeder;
use App\Schedule;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'birth' => '1995',
            'gender' => 'M',
            'university' => 'University of Moratuwa',
            'major' => 'Computer Science',
            'country' => 'GB',
            'password' => bcrypt('password'),
        ]);

        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'birth' => '1995',
            'gender' => 'F',
            'university' => 'University of Moratuwa',
            'major' => 'Law',
            'country' => 'LK',
            'password' => bcrypt('password'),
        ]);

        $schedule = new Schedule();
        $schedule->createSchedule($user, factory(App\Schedule::class)->make());

        
        factory(App\User::class, 10)->create()->each(function ($user){
            $schedule = new Schedule();
            $schedule->createSchedule($user, factory(App\Schedule::class)->make());
            $user->completed_modules()->saveMany(factory(App\CompletedModule::class, 10)->make());
        });
    }
}
