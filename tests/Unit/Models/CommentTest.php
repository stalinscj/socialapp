<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use App\Traits\HasLikes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_comment_belongs_to_user()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(User::class, $comment->user);
    }

    /**
     * @test
     */
    public function a_comment_belongs_to_status()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(Status::class, $comment->status);
    }

    /**
     * @test
     */
    public function comment_model_must_use_the_has_likes_trait()
    {
        $this->assertClassUsesTrait(Comment::class, HasLikes::class);
    }

    /**
     * @test
     */
    public function a_comment_must_have_a_path()
    {
        $comment = Comment::factory()->create();

        $expectedPath = route('statuses.show', $comment->status) . '#comment-' . $comment->id;

        $this->assertEquals($expectedPath, $comment->getPath());
    }

}
