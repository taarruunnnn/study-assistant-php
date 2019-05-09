<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RouteTest extends DuskTestCase
{
    use WithFaker;

    /**
     * Route - Login
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Login');
        });
    }

    /**
     * Route - Register
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testRegister()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->assertSee('Register');
        });
    }

    /**
     * Authenticated Routes
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testUnauthenticated()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard')
                    ->assertSee('Login')
                    ->visit('/schedules')
                    ->assertSee('Login')
                    ->visit('/schedules/create')
                    ->assertSee('Login')
                    ->visit('/session')
                    ->assertSee('Login')
                    ->visit('/reports')
                    ->assertSee('Login')
                    ->visit('/user/profile')
                    ->assertSee('Login')
                    ->visit('/user/edit')
                    ->assertSee('Login')
                    ->visit('/help')
                    ->assertSee('Login');
        });
    }

    /**
     * Route - Dashboard
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testDashboard()
    {
        try {
            $user = User::where('email', 'jane@example.com')->firstOrFail();
        } catch(ModelNotFoundException $e) {
            $user = factory(User::class)->create(
                [
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com',
                    'password' => 'password'
                ]
            );
        }
        
        $this->browse(
            function ($browser) use ($user) {
                $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->assertTitle('Study Assistant - Dashboard');
            }
        );

        return $user;
    }

    /**
     * Route - Schedule
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testSchedule()
    {
        
        $this->browse(
            function ($browser){
                $browser->visit('/schedules')
                    ->assertTitle('Study Assistant - Schedule');
            }
        );

    }

    /**
     * Route - Session
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testSession()
    {
        
        $this->browse(
            function ($browser){
                $browser->visit('/session')
                    ->assertTitle('Study Assistant - Study Session');
            }
        );

    }

    /**
     * Route - User Profile
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testUserProfile()
    {
        
        $this->browse(
            function ($browser){
                $browser->visit('/user/profile')
                    ->assertTitle('Study Assistant - User Profile');
            }
        );

    }

    /**
     * Route - Edit User
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testEditUser()
    {
        
        $this->browse(
            function ($browser){
                $browser->visit('/user/edit')
                    ->assertTitle('Study Assistant - Edit User');
            }
        );

    }

    /**
     * Route - Reports
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testReports()
    {
        
        $this->browse(
            function ($browser){
                $browser->visit('/reports')
                    ->assertTitle('Study Assistant - Reports');
            }
        );

    }

    /**
     * Route - Help
     * 
     * @test
     * @group routes
     *
     * @return void
     */
    public function testHelp()
    {
        
        $this->browse(
            function ($browser){
                $browser->visit('/help')
                    ->assertTitle('Study Assistant - Help');
            }
        );

    }
}
