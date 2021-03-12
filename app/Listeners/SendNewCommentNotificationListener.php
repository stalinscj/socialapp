<?php

namespace App\Listeners;

use App\Events\CommentCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NewCommentNotification;

class SendNewCommentNotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\CommentCreatedEvent $event
     * @return void
     */
    public function handle(CommentCreatedEvent $event)
    {
        $event->comment->status->user
            ->notify(new NewCommentNotification($event->comment));
    }
}
