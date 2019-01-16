<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * Tests user creation
     *
     * @return void
     */
    public function testUserCreation()
    {
        $user = factory(User::class)->create();

        $retrievedUser = User::latest()->get();

        $this->assertEquals($user->toArray(), $retrievedUser[0]->toArray());

        return $user;
    }

    /**
     * Tests whether logged in users are redirected
     * to the dashboard
     *
     * @depends testUserCreation
     * 
     * @return void
     */
    public function testUserRedirect(User $user)
    {
        $this->actingAs($user)
            ->get('/')
            ->assertSee('dashboard');
    }

}
