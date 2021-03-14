<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewLikeNotification extends Notification
{
    use Queueable;

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
     * Create a new notification instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \App\Models\User $likeSender
     * @return void
     */
    public function __construct($model, $likeSender)
    {
        $this->model      = $model;
        $this->likeSender = $likeSender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $modelName = __('lang.'.class_basename($this->model));

        return [
            'link'    => $this->model->getPath(),
            'message' => "A {$this->likeSender->name} le gustó tu $modelName",
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
