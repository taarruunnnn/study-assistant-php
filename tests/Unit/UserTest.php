<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Tests user creation
     *
     * @return void
     */
    public function testUserCreation()
    {
        $name = 'Test User';
        
        $user = factory(User::class)->create([
            'name' => $name
        ]);

        $retrievedUser = User::where('name', $name)->first();

        $this->assertEquals($user->name, $retrievedUser->name);

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
