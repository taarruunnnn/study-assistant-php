<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelperTest extends TestCase
{
    /**
     * Test Schedule retriever function
     *
     * @return void
     */
    public function testScheduleRetriever()
    {
        $user = User::has('schedule')->first();

        $data = scheduleRetriever($user);

        $this->assertInternalType("array", $data);
    }

    /**
     * Test the output of Schedule Retriever
     * when no schedule is available
     *
     * @return void
     */
    public function testScheduleRetrieverWithoutSchedule()
    {
        $user = User::doesntHave('schedule')->first();

        $data = scheduleRetriever($user);

        $this->assertEmpty($data);
    }
}
