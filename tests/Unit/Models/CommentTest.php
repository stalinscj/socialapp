<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Status;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_comment_belongs_to_status()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(Status::class, $comment->status);
    }
}
