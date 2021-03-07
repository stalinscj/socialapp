<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function route_key_name_is_set_to_name()
    {
        $user = User::factory()->make();

        $this->assertEquals('name', $user->getRouteKeyName());
    }

    /**
     * @test
     */
    public function user_has_a_link_to_their_profile()
    {
        $user = User::factory()->make();

        $this->assertEquals(route('users.show', $user), $user->link());
    }

    /**
     * @test
     */
    public function user_has_an_avatar()
    {
        $user = User::factory()->make();

        $this->assertEquals('img/default-avatar.jpg', $user->avatar());

        $this->assertEquals('img/default-avatar.jpg', $user->avatar);
    }

}
