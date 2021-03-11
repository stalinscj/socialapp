<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Factories\DatabaseNotificationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanManageNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function guests_users_cannot_get_their_notifications()
    {
        $this->getJson(route('notifications.index'))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function authenticated_users_can_get_their_notifications()
    {
        $user = $this->signIn();

        $notification = DatabaseNotificationFactory::new()->for($user, 'notifiable')->create();

        $response = $this->getJson(route('notifications.index'));

        $response->assertSuccessful();

        $response->assertJson([
            [
                'data' => [
                    'link'    => $notification->data['link'],
                    'message' => $notification->data['message'],
                ]
            ]
        ]);

    }

    /**
     * @test
     */
    function guests_users_cannot_mark_notifications()
    {
        $notification = DatabaseNotificationFactory::new()->create();

        $this->postJson(route('read-notifications.store', $notification))
            ->assertUnauthorized();
        
        $this->deleteJson(route('read-notifications.destroy', $notification))
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function authenticated_users_can_mark_notifications_as_read()
    {
        $user = $this->signIn();

        $notification = DatabaseNotificationFactory::new()->for($user, 'notifiable')->create();

        $response = $this->postJson(route('read-notifications.store', $notification));

        $response->assertSuccessful();

        $this->assertNotNull($notification->fresh()->read_at);

        $response->assertJson($notification->fresh()->toArray());
    }

    /**
     * @test
     */
    public function authenticated_users_can_mark_notifications_as_unread()
    {
        $user = $this->signIn();

        $notification = DatabaseNotificationFactory::new()->for($user, 'notifiable')->read()->create();

        $response = $this->deleteJson(route('read-notifications.destroy', $notification));

        $response->assertSuccessful();

        $this->assertNull($notification->fresh()->read_at);

        $response->assertJson($notification->fresh()->toArray());
    }

}
