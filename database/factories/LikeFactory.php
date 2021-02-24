<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $likeable = $this->faker->randomElement([Status::factory(), Comment::factory()]);

        return [
            'user_id'       => User::factory(),
            'likeable_id'   => $likeable,
            'likeable_type' => $likeable->modelName(),
        ];
    }
}
