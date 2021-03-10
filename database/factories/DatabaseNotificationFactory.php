<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseNotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DatabaseNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $notifiable = User::factory();

        return [
            'id'              => Str::uuid()->toString(),
            'type'            => 'App\\Notifications\\FakeNotification',
            'notifiable_id'   => $notifiable,
            'notifiable_type' => $notifiable->modelName(),
            'data'            => [
                'link'    => url('/'),
                'message' => "Notification's message"
            ],
            'read_at'         => null,
        ];
    }

    /**
     * Indicate that the notification should be read.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function read()
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => now(),
            ];
        });
    }
}
