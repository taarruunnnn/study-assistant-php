<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends DuskTestCase
{
    use WithFaker;
    
    /**
     * Tests user login functionality
     * 
     * ID - TF001
     *
     * @return User
     */
    public function testUserLogin()
    {
        $email = $this->faker->unique()->safeEmail;

        $user = factory(User::class)->create(
            [
                'email' => $email,
            ]
        );

        $this->browse(
            function ($browser) use ($user) {
                $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/dashboard');
            }
        );

        return $user;
    }

    /**
     * Tests user editing functionality
     * 
     * ID - TF002
     *
     * @param User $user Newly created User
     * 
     * @depends testUserLogin
     * 
     * @return void
     */
    public function testUserEdit($user)
    {
        $this->browse(
            function ($browser) use ($user) {
                $name = $this->faker->name;

                $browser->loginAs($user)
                    ->visit('/user/edit')
                    ->assertTitle('Study Assistant - Edit User')
                    ->type('name', $name)
                    ->type('birth', '1995')
                    ->click('#gender label')
                    ->click('#maleBtn')
                    ->select('country', 'LK')
                    ->type('university', 'University of Colombo')
                    ->select('major', 'Computer Science')
                    ->press('Update Profile')
                    ->assertVisible('.toast-success');
            }
        );
    }

    /**
     * Tests user loggin out functionality
     * 
     * ID - TF003
     *
     * @param User $user Newly created user
     * 
     * @depends testUserLogin
     * 
     * @return void
     */
    public function testUserLogout($user)
    {
        $this->browse(
            function ($browser) use ($user) {
                $browser->click('#navbarDropdown')
                    ->clickLink('Logout')
                    ->assertPathIs('/');
            }
        );
    }

    /**
     * Tests user registration functionality
     * 
     * ID - TF004
     *
     * @return void
     */
    public function testUserRegistration()
    {
        $email = $this->faker->unique()->safeEmail;

        $this->browse(
            function ($browser) use ($email) {
                $name = $this->faker->name;
            
                $browser->visit('/register')
                    ->type('name', $name)
                    ->type('email', $email)
                    ->type('birth', '1990')
                    ->click('#genderLabel')
                    ->click('#femaleLbl')
                    ->select('country', 'LK')
                    ->type('university', 'University of Colombo')
                    ->select('major', 'Computer Science')
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password')
                    ->press('Register')
                    ->assertPathIs('/dashboard');

            }
        );

        $user = User::where('email', $email)->first();
        return $user;
    }

    /**
     * Tests user deleting functionality
     * Deletes both users created in the test
     * 
     * ID - TF005
     *
     * @param User $user1 Newly created user 1
     * @param User $user2 Newly created user 2
     * 
     * @depends testUserLogin
     * @depends testUserRegistration
     * 
     * @return void
     */
    public function testUserDelete($user1, $user2) 
    {
        $this->browse(
            function ($browser) use ($user1) {
                $browser->loginAs($user1)
                    ->visit('user/edit')
                    ->click('#deleteBtn')
                    ->pause(1000)
                    ->press('Yes')
                    ->pause(100)
                    ->assertPathIs('/');
            }
        );

        $this->browse(
            function ($browser) use ($user2) {
                $browser->loginAs($user2)
                    ->visit('user/edit')
                    ->click('#deleteBtn')
                    ->pause(1000)
                    ->press('Yes')
                    ->assertPathIs('/');
            }
        );
    }
}
