<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendshipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Friendship::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender_id'    => User::factory(),
            'recipient_id' => User::factory(),
            'accepted'     => false
        ];
    }

    /**
     * Indicate that the status of the friendship should be accepted.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function accepted()
    {
        return $this->state(function (array $attributes) {
            return [
                'accepted' => true,
            ];
        });
    }
}
