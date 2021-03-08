<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FriendshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_friendship_belongs_to_sender()
    {
        $friendship = Friendship::factory()->create();

        $this->assertInstanceOf(User::class, $friendship->sender);
    }

    /**
     * @test
     */
    public function a_friendship_belongs_to_recipient()
    {
        $friendship = Friendship::factory()->create();

        $this->assertInstanceOf(User::class, $friendship->recipient);
    }

}
