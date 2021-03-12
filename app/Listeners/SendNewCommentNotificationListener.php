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
        $notifiable = $event->comment->status->user;
        
        if ($notifiable->isNot($event->comment->user)) {
            $notifiable->notify(new NewCommentNotification($event->comment));
        }
    }
}
