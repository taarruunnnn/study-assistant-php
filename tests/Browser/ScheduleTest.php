<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleTest extends DuskTestCase
{
    use WithFaker;
    /**
     * Tests schedule creation
     * 
     * ID - TF006
     *
     * @return User
     */
    public function testCreateSchedule()
    {
        $email = $this->faker->unique()->safeEmail;

        $user = factory(User::class)->create(
            [
                'email' => $email,
            ]
        );

        $this->browse(
            function (Browser $browser) use ($user) {
                $browser->loginAs($user)
                    ->visit('/schedules')
                    ->assertTitle('Study Assistant - Schedule')
                    ->press('Create Schedule')
                    ->assertPathIs('/schedules/create')
                    ->type('#module-name', 'English')
                    ->select('#module-rating', '8')
                    ->press('Add')
                    ->type('#start', '2019-01-02')
                    ->type('#end', '2019-02-02')
                    ->press('Create Schedule')
                    ->assertVisible('.alert-success')
                    ->assertPathIs('/schedules');
            }
        );

        return $user;
    }

    /**
     * Tests schedule editing
     * 
     * ID - TF007
     *
     * @param User $user newly created user
     * 
     * @depends testCreateSchedule
     * 
     * @return void
     */
    public function testEditSchedule($user)
    {
        $this->browse(
            function ($browser) use ($user) {
                $browser->visit('/schedules')
                    ->press('  Modify Schedule')
                    ->pause(1000)
                    ->type('#start', '2019-02-02')
                    ->type('#end', '2019-03-02')
                    ->select('#weekends', '4')
                    ->press('Save Changes')
                    ->assertVisible('.alert-success')
                    ->assertPathIs('/schedules');
            }
        );
    }

    /**
     * Tests schedule deletion
     * 
     * ID - TF008
     *
     * @param User $user newly created user
     * 
     * @depends testCreateSchedule
     * 
     * @return void
     */
    public function testDeleteSchedule($user)
    {
        $this->browse(
            function ($browser) use ($user) {
                $browser->visit('/schedules')
                    ->press('  Modify Schedule')
                    ->pause(1000)
                    ->press('Delete Schedule')
                    ->waitFor('#btnYes', 1)
                    ->press('#btnYes')
                    ->assertVisible('.alert-success')
                    ->assertPathIs('/schedules');
            }
        );
    }
}
