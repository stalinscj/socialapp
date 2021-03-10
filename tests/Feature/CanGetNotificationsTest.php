<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Factories\DatabaseNotificationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanGetNotificationTest extends TestCase
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

}
