<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use App\Models\Friendship;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        
        Status::factory()
            ->hasLikes(rand(1, 5))
            ->has(Comment::factory(rand(1, 5))->hasLikes(rand(1, 5)))
            ->create();

        Status::factory()
            ->hasLikes(rand(1, 5))
            ->has(Comment::factory(rand(1, 5))->hasLikes(rand(1, 5)))
            ->create();
        
        Status::factory()
            ->hasLikes(rand(1, 5))
            ->has(Comment::factory(rand(1, 5))->hasLikes(rand(1, 5)))
            ->create();

        Status::factory()
            ->hasLikes(rand(1, 5))
            ->has(Comment::factory(rand(1, 5))->hasLikes(rand(1, 5)))
            ->create();

        Status::factory()
            ->hasLikes(rand(1, 5))
            ->has(Comment::factory(rand(1, 5))->hasLikes(rand(1, 5)))
            ->create();


        User::where('id', '!=', 1)
            ->limit(rand(5, 10))
            ->get()
            ->each(function ($user) {
                Friendship::factory()
                    ->create([
                        'sender_id' => $user->id,
                        'recipient_id' => 1
                    ]);
            });

    }
}
