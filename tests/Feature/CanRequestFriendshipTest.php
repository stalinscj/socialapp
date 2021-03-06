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
    public function can_get_all_friendship_requests_received()
    {
        $recipient = $this->signIn();
        $sender    = User::factory()->create();

        $sender->sendFriendRequestTo($recipient);
        Friendship::factory(2)->create();

        $response = $this->get(route('accept-friendships.index'));

        $this->assertCount(1, $response->viewData('friendshipRequests'));
    }

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
    public function a_sender_cannot_send_friendship_request_to_itself()
    {
        $sender = $this->signIn();

        $this->postJson(route('friendships.store', $sender));

        $this->assertDatabaseMissing('friendships', [
            'sender_id'    => $sender->id,
            'recipient_id' => $sender->id,
            'status'       => Friendship::STATUS_PENDING,
        ]);
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
    public function senders_can_delete_sent_friendship_request()
    {
        $friendship = Friendship::factory()->create();

        $this->signIn($friendship->sender);

        $response = $this->deleteJson(route('friendships.destroy', $friendship->recipient))
            ->assertSuccessful();
        
        $response->assertJson([
            'friendship_status' => 'DELETED',
        ]);

        $this->assertDatabaseMissing('friendships', [
            'sender_id'    => $friendship->sender->id,
            'recipient_id' => $friendship->recipient->id,
        ]);
    }

    /**
     * @test
     */
    public function senders_cannot_delete_denied_friendship_request()
    {
        $friendship = Friendship::factory()->denied()->create();

        $this->signIn($friendship->sender);

        $response = $this->deleteJson(route('friendships.destroy', $friendship->recipient))
            ->assertSuccessful();
        
        $response->assertJson([
            'friendship_status' => Friendship::STATUS_DENIED,
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id'    => $friendship->sender->id,
            'recipient_id' => $friendship->recipient->id,
            'status'       => Friendship::STATUS_DENIED,
        ]);
    }

    /**
     * @test
     */
    public function recipients_can_delete_denied_friendship_request()
    {
        $friendship = Friendship::factory()->denied()->create();

        $this->signIn($friendship->recipient);

        $response = $this->deleteJson(route('friendships.destroy', $friendship->sender))
            ->assertSuccessful();
        
        $response->assertJson([
            'friendship_status' => 'DELETED',
        ]);

        $this->assertDatabaseMissing('friendships', [
            'sender_id'    => $friendship->sender->id,
            'recipient_id' => $friendship->recipient->id,
            'status'       => Friendship::STATUS_DENIED,
        ]);
    }

    /**
     * @test
     */
    public function recipients_can_delete_recived_friendship_request()
    {
        $friendship = Friendship::factory()->create();

        $this->signIn($friendship->recipient);

        $response = $this->deleteJson(route('friendships.destroy', $friendship->sender))
            ->assertSuccessful();
        
        $response->assertJson([
            'friendship_status' => 'DELETED',
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

        $this->get(route('accept-friendships.index'))
            ->assertRedirect('login');
        
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

        $response = $this->postJson(route('accept-friendships.store', $friendship->sender))
            ->assertSuccessful();
        
        $response->assertJson([
            'friendship_status' => Friendship::STATUS_ACCEPTED,
        ]);

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

        $response = $this->deleteJson(route('accept-friendships.destroy', $friendship->sender))
            ->assertSuccessful();
        
        $response->assertJson([
            'friendship_status' => Friendship::STATUS_DENIED,
        ]);

        $this->assertDatabaseHas('friendships', [
            'sender_id'    => $friendship->sender->id,
            'recipient_id' => $friendship->recipient->id,
            'status'       => Friendship::STATUS_DENIED,
        ]);
    }
}
