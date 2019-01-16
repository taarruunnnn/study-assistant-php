<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestTest extends TestCase
{
    /**
     * Tests whether a guest user trying to access protected
     * routes is redirected to login screen
     *
     * @return void
     */
    public function testGuestRedirect()
    {
        $this->get('/dashboard')
            ->assertSee('login');

        $this->get('/user/edit')
            ->assertSee('login');

        $this->get('/schedules')
            ->assertSee('login');

        $this->get('/reports')
            ->assertSee('login');

        $this->get('/session')
            ->assertSee('login');
    }
}
