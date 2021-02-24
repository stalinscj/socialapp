<?php

namespace Tests\Unit\Http\Resources;

use Tests\TestCase;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_comment_resource_must_have_the_necesary_fields()
    {
        $comment = Comment::factory()->create();

        $commentResource = CommentResource::make($comment)->resolve();

        $this->assertEquals($comment->body, $commentResource['body']);
        
        $this->assertEquals($comment->user->name, $commentResource['user_name']);
        
        $this->assertEquals('img/default-avatar.jpg', $commentResource['user_avatar']);
    }
}
