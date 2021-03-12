<?php

namespace App\Providers;

use App\Events\ModelLikedEvent;
use App\Events\CommentCreatedEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendNewLikeNotificationListener;
use App\Listeners\SendNewCommentNotificationListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ModelLikedEvent::class => [
            SendNewLikeNotificationListener::class,
        ],
        CommentCreatedEvent::class => [
            SendNewCommentNotificationListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
