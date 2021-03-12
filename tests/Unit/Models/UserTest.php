<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertEquals('/img/default-avatar.jpg', $user->avatar());

        $this->assertEquals('/img/default-avatar.jpg', $user->avatar);
    }

    /**
     * @test
     */
    public function an_user_has_many_statuses()
    {
        $user = User::factory()->hasStatuses(2)->create();

        $this->assertInstanceOf(Status::class, $user->statuses->first());
    }

    /**
     * @test
     */
    public function an_user_can_send_friend_requests()
    {
        $sender    = User::factory()->create();
        $recipient = User::factory()->create();

        $friendship = $sender->sendFriendRequestTo($recipient);

        $this->assertTrue($friendship->sender->is($sender));
        $this->assertTrue($friendship->recipient->is($recipient));
        $this->assertEquals(Friendship::STATUS_PENDING, $friendship->status);
    }

    /**
     * @test
     */
    public function an_user_can_accept_friend_requests()
    {
        $sender    = User::factory()->create();
        $recipient = User::factory()->create();

        $sender->sendFriendRequestTo($recipient);

        $friendship = $recipient->acceptFriendRequestFrom($sender);

        $this->assertEquals(Friendship::STATUS_ACCEPTED, $friendship->status);
    }

}
