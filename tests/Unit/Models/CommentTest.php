<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Like;
use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
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
    public function a_comment_morph_many_likes()
    {
        $comment = Comment::factory()->hasLikes(2)->create();

        $this->assertInstanceOf(Like::class, $comment->likes->first());
    }

    /**
     * @test
     */
    public function a_comment_can_be_liked_and_unliked()
    {
        $user = $this->signIn();
        $comment = Comment::factory()->create();

        $comment->like($user);

        $this->assertEquals(1, $comment->likes->count());

        $comment->unlike($user);

        $this->assertEquals(0, $comment->fresh()->likes->count());
    }

    /**
     * @test
     */
    public function a_comment_can_be_liked_once()
    {
        $user = $this->signIn();
        $comment = Comment::factory()->create();

        $comment->like($user);

        $this->assertEquals(1, $comment->likes->count());

        $comment->like($user);

        $this->assertEquals(1, $comment->fresh()->likes->count());
    }

    /**
     * @test
     */
    public function a_comment_knows_if_has_been_liked()
    {
        $user = $this->signIn();
        $comment = Comment::factory()->create();

        $this->assertFalse($comment->isLiked($user));

        $comment->like($user);

        $this->assertTrue($comment->isLiked($user));
    }

    /**
     * @test
     */
    public function a_comment_knows_how_many_likes_it_has()
    {
        $comment = Comment::factory()->create();

        $this->assertEquals(0, $comment->likesCount());

        Like::factory(2)->for($comment, 'likeable')->create();

        $this->assertEquals(2, $comment->likesCount());
    }
}
