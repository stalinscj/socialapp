<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\Comment;
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

    }
}
