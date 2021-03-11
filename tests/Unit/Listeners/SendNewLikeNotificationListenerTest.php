<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\Like;
use App\Models\Status;
use App\Events\ModelLikedEvent;
use App\Notifications\NewLikeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendNewLikeNotificationListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_notification_is_sent_when_a_user_receives_a_new_like()
    {
        Notification::fake();

        $status = Status::factory()->create();

        $like = Like::factory()->for($status, 'likeable')->create();

        ModelLikedEvent::dispatch($status, $like->user);

        Notification::assertSentTo(
            $status->user, 
            NewLikeNotification::class, 
            function ($newLikeNotification, $channels) use ($status, $like) {
                $this->assertContains('database', $channels);
                $this->assertContains('broadcast', $channels);
                $this->assertTrue($newLikeNotification->model->is($status));
                $this->assertTrue($newLikeNotification->likeSender->is($like->user));
                
                return true;
            }
        );
        
    }
}
