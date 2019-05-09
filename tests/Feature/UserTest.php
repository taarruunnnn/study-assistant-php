<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    /**
     * Tests whether a logged in user is redirected to
     * the dashboard
     *
     * @return void
     */
    public function testLogin()
    {
        $response = $this->withHeaders(
            [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'
            ]
        )->json('POST', '/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com', 
            'birth' => 1995,
            'gender' => 'M',
            'country' => 'LK',
            'university' => 'University of Colombo',
            'major' => 'Biology',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertRedirect('/dashboard');
    }

    /**
     * Tests whether user editing feature is working
     *
     * @return void
     */
    public function testUserEdit()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get('/user/edit')
            ->assertStatus(200);

        $this->actingAs($user)
            ->post(
                '/user/update', [
                    '_method' => 'PATCH',
                    'name' => 'Jessie Doe',
                    'email' => $this->faker->unique()->safeEmail,
                    'birth' => 1990,
                    'gender' => 'F',
                    'country' => 'LK',
                    'university' => 'University of Colombo',
                    'major' => 'Computer Science'
                ]
            )
            ->assertStatus(302);

        $this->assertEquals($user->name, 'Jessie Doe');

        return $user;
    }

    /**
     * Delete user
     *
     * @param User $user A User Object
     * 
     * @depends testUserEdit
     * 
     * @return void
     */
    public function testUserDelete($user)
    {
        $this->actingAs($user)
            ->post('/user/delete/'.$user->name, ['_method' => 'DELETE'])
            ->assertStatus(302);
    }
}
