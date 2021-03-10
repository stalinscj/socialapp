<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ModelLikedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Model liked
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * User who likes the model
     *
     * @var \App\Models\User
     */
    public $likeSender;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \App\Models\User $likeSender
     * @return void
     */
    public function __construct($model, $likeSender)
    {
        $this->model      = $model;
        $this->likeSender = $likeSender;

        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->model->getEventChannelName());
    }
}
