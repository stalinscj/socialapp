<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanSeeProfilesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_see_profiles()
    {
        $user = User::factory()->create();

        $this->get(route('users.show', $user))
            ->assertViewIs('users.show')
            ->assertSee($user->name);
    }
}
