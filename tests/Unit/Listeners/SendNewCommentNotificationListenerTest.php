<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\Comment;
use App\Events\CommentCreatedEvent;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendNewCommentNotificationListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_notification_is_sent_when_a_status_receives_a_new_comment()
    {
        Notification::fake();

        $comment = Comment::factory()->create();

        CommentCreatedEvent::dispatch($comment);

        Notification::assertSentTo(
            $comment->status->user, 
            NewCommentNotification::class, 
            function ($newCommentNotification, $channels) use ($comment) {
                $this->assertContains('database', $channels);
                $this->assertTrue($newCommentNotification->comment->is($comment));
                
                return true;
            }
        );
        
    }
}
