<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\User;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

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
        $date = Carbon::now();
        $start = $date->toDateString();
        $end = $date->addMonths(3)->toDateString();

        $user = factory(User::class)->create(
            [
                'email' => $email,
            ]
        );

        $this->browse(
            function (Browser $browser) use ($user, $start, $end) {
                $browser->loginAs($user)
                    ->visit('/schedules')
                    ->assertTitle('Study Assistant - Schedule')
                    ->press('Create Schedule')
                    ->assertPathIs('/schedules/create')
                    ->type('#module-name', 'English')
                    ->select('#module-rating', '8')
                    ->press('Add')
                    ->keys('#start', $start, '{enter}')
                    ->keys('#end', $end, '{enter}')
                    ->press('Create Schedule')
                    ->assertVisible('.toast-success')
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
        $date = Carbon::now();
        $start = $date->addMonths(1)->toDateString();

        $this->browse(
            function ($browser) use ($user, $start) {
                $browser->loginAs($user)
                    ->visit('/schedules')
                    ->pause(500)
                    ->press('Modify Schedule')
                    ->pause(500)
                    ->keys('#start', $start, '{enter}')
                    ->select('#weekends', '4')
                    ->press('Save Changes')
                    ->assertVisible('.toast-success')
                    ->pause(500)
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
                $browser->loginAs($user)
                    ->visit('/schedules')
                    ->pause(500)
                    ->press('Modify Schedule')
                    ->pause(500)
                    ->press('Delete Schedule')
                    ->waitFor('#btnYes', 1)
                    ->press('#btnYes')
                    ->assertVisible('.toast-success')
                    ->assertPathIs('/schedules');
            }
        );
    }
}
