<?php

namespace App\Listeners;


use App\Events\ModelLikedEvent;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NewLikeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewLikeNotificationListener
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
     * @param \App\Events\ModelLikedEvent $modelLikedEvent
     * @return void
     */
    public function handle(ModelLikedEvent $modelLikedEvent)
    {
        $modelLikedEvent->model->user->notify(new NewLikeNotification);
    }
}
