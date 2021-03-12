<?php

namespace Tests\Unit\Notifications;

use Tests\TestCase;
use App\Models\Like;
use App\Models\Status;
use App\Notifications\NewLikeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewLikeNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function the_new_like_notification_is_stored_in_the_database()
    {
        $status = Status::factory()->create();

        $like = Like::factory()->for($status, 'likeable')->create();

        $status->user->notify(new NewLikeNotification($status, $like->user));

        $this->assertCount(1, $status->user->notifications);

        $notificationData = $status->user->notifications->first()->data;

        $this->assertEquals($status->getPath(), $notificationData['link']);
        $this->assertEquals("A {$like->user->name} le gustó tu publicación", $notificationData['message']);
    }
}
