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
    public function guests_cannot_create_friendship_request()
    {
        $recipient = User::factory()->create();

        $this->postJson(route('friendships.store', $recipient))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function can_create_friendship_request()
    {
        $sender = $this->signIn();

        $recipient = User::factory()->create();

        $response = $this->postJson(route('friendships.store', $recipient))
            ->assertSuccessful();
        
        $response->assertJson([
            'friendship_status' => Friendship::STATUS_PENDING,
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id'    => $sender->id,
            'recipient_id' => $recipient->id,
            'status'       => Friendship::STATUS_PENDING,
        ]);

        $this->postJson(route('friendships.store', $recipient));

        $this->assertCount(1, Friendship::all());
    }

    /**
     * @test
     */
    public function guests_cannot_delete_friendship_request()
    {
        $friendship = Friendship::factory()->create();

        $this->deleteJson(route('friendships.destroy', $friendship->recipient))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function can_delete_friendship_request()
    {
        $friendship = Friendship::factory()->create();

        $this->signIn($friendship->sender);

        $response = $this->deleteJson(route('friendships.destroy', $friendship->recipient))
            ->assertSuccessful();
        
        $response->assertJson([
            'friendship_status' => '',
        ]);

        $this->assertDatabaseMissing('friendships', [
            'sender_id'    => $friendship->sender->id,
            'recipient_id' => $friendship->recipient->id,
        ]);
    }

    /**
     * @test
     */
    public function guests_cannot_accept_friendship_request()
    {
        $friendship = Friendship::factory()->create();

        $this->postJson(route('accept-friendships.store', $friendship->sender))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function can_accept_friendship_request()
    {
        $friendship = Friendship::factory()->create();

        $this->signIn($friendship->recipient);

        $this->postJson(route('accept-friendships.store', $friendship->sender));

        $this->assertDatabaseHas('friendships', [
            'sender_id'    => $friendship->sender->id,
            'recipient_id' => $friendship->recipient->id,
            'status'       => Friendship::STATUS_ACCEPTED,
        ]);
    }

    /**
     * @test
     */
    public function guests_cannot_deny_friendship_request()
    {
        $friendship = Friendship::factory()->create();

        $this->deleteJson(route('accept-friendships.destroy', $friendship->sender))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function can_deny_friendship_request()
    {
        $friendship = Friendship::factory()->create();

        $this->signIn($friendship->recipient);

        $this->deleteJson(route('accept-friendships.destroy', $friendship->sender));

        $this->assertDatabaseHas('friendships', [
            'sender_id'    => $friendship->sender->id,
            'recipient_id' => $friendship->recipient->id,
            'status'       => Friendship::STATUS_DENIED,
        ]);
    }
}
