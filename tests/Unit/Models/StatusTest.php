<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use App\Traits\HasLikes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_status_belongs_to_user()
    {
        $status = Status::factory()->create();

        $this->assertInstanceOf(User::class, $status->user);
    }

    /**
     * @test
     */
    public function a_status_has_many_comments()
    {
        $status = Status::factory()->hasComments(2)->create();

        $this->assertInstanceOf(Comment::class, $status->comments->first());
    }

    /**
     * @test
     */
    public function status_model_must_use_the_has_likes_trait()
    {
        $this->assertClassUsesTrait(Status::class, HasLikes::class);
    }

}
