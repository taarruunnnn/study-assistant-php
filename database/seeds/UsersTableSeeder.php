<?php

use Illuminate\Database\Seeder;

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
            'university' => 'University of Randomness',
            'country' => 'GB',
            'password' => bcrypt('password'),
        ]);

        factory(App\User::class, 10)->create();
    }
}
