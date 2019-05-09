<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Schedule;
use App\Http\Requests\StoreSchedule;
use App\Session;

class ScheduleTest extends TestCase
{
    /**
     * Testing the route
     *
     * @return void
     */
    public function testScheduleShow()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get('/schedules')
            ->assertStatus(302);

        return $user;
    }

    /**
     * Testing the schedule creation
     * 
     * Schedule is created and its validity is tested
     * by counting the total number of sessions per
     * module and comparing them to verify that the 
     * module with the highest rating has the highest
     * number of sessions.
     *
     * @param User $user A user object
     * 
     * @depends testScheduleShow
     * 
     * @return void
     */
    public function testCreateSchedule($user)
    {
        $request = new StoreSchedule();
        $request->replace(
            [
                'start' => '2019-01-01',
                'end' => '2019-03-21',
                'weekdays' => 2,
                'weekends' => 2,
                'module' => ['IT Project Management', 'Computational Mathematics', 'Business Studies'],
                'rating' => [3, 5, 2]
            ]
        );

        $schedule = new Schedule;
        $schedule = $schedule->createSchedule($user, $request);

        // Comp Mathematics > ITPM > BS
        $itpm_count = $schedule->sessions()->where("module", "IT Project Management")->get()->count();
        $cm_count = $schedule->sessions()->where("module", "Computational Mathematics")->get()->count();
        $bs_count = $schedule->sessions()->where("module", "Business Studies")->get()->count();        

        $this->assertGreaterThanOrEqual($bs_count, $itpm_count);
        $this->assertGreaterThanOrEqual($bs_count, $cm_count);
        $this->assertGreaterThanOrEqual($itpm_count, $cm_count);

    }

    /**
     * Test moving sessions
     *
     * @param User $user user object
     * 
     * @depends testScheduleShow
     * 
     * @return void
     */
    public function testMoveSession($user)
    {
        $currentUser = User::where('id', $user->id)->first();
        $id = $currentUser->schedule->sessions->first()->id;

        $this->actingAs($currentUser)
            ->post(
                '/schedules/move', [
                    'id' => $id,
                    'date' => '2019-02-20'   
                ]
            )->assertStatus(302);

        $session = Session::find($id);
        $this->assertEquals('2019-02-20', $session->date);

    }

    /**
     * Testing schedule deletion
     *
     * @param User $user A user object
     * 
     * @depends testScheduleShow
     * 
     * @return void
     */
    public function testDeleteSchedule($user)
    {
        $currentUser = User::where('id', $user->id)->first();
        $this->actingAs($currentUser)
            ->get('/schedules/destroy')
            ->assertStatus(302);
    }
}
