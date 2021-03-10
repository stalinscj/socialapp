<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
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

        ModelLikedEvent::dispatch($status);

        Notification::assertSentTo($status->user, NewLikeNotification::class);
        
    }
}
