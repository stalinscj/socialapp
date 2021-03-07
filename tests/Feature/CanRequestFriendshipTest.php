<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanRequestFriendshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_send_friendschip_request()
    {
        $sender = $this->signIn();

        $recipient = User::factory()->create();

        $this->postJson(route('friendships.store', $recipient));

        $this->assertDatabaseHas('friendships', [
            'sender_id'   => $sender->id,
            'recipient_id' => $recipient->id,
            'accepted'    => false,
        ]);
    }

    /**
     * @test
     */
    public function can_accept_friendschip_request()
    {
        $friendship = Friendship::factory()->create();

        $this->signIn($friendship->recipient);

        $this->postJson(route('request-friendships.store', $friendship->sender));

        $this->assertDatabaseHas('friendships', [
            'sender_id'    => $friendship->sender->id,
            'recipient_id' => $friendship->recipient->id,
            'accepted'     => true,
        ]);
    }
}
