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

    /**
     * @test
     */
    public function can_find_friendships_by_sender_and_recipient()
    {
        $sender    = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::factory(2)->for($sender, 'sender')->create();
        Friendship::factory(2)->for($recipient, 'recipient')->create();
        
        Friendship::create([
            'sender_id'    => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $foundFriendship = Friendship::betweenUsers($sender, $recipient)->first();

        $this->assertEquals($sender->id,    $foundFriendship->sender_id);
        $this->assertEquals($recipient->id, $foundFriendship->recipient_id);

        $foundFriendshipInverted = Friendship::betweenUsers($recipient, $sender)->first();

        $this->assertEquals($sender->id,     $foundFriendshipInverted->sender_id);
        $this->assertEquals($recipient->id,  $foundFriendshipInverted->recipient_id);
    }

}
