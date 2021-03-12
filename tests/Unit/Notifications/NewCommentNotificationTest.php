<?php

namespace Tests\Unit\Notifications;

use Tests\TestCase;
use App\Models\Comment;
use App\Notifications\NewCommentNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewCommentNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function the_new_comment_notification_is_stored_in_the_database()
    {
        $comment = Comment::factory()->create();

        $comment->status->user->notify(new NewCommentNotification($comment));

        $this->assertCount(1, $comment->status->user->notifications);

        $notificationData = $comment->status->user->notifications->first()->data;

        $this->assertEquals($comment->getPath(), $notificationData['link']);
        $this->assertEquals("{$comment->user->name} comentó tu publicación", $notificationData['message']);
    }
}
