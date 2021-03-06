<?php

namespace Tests\Unit\Http\Resources;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use App\Http\Resources\UserResource;
use App\Http\Resources\StatusResource;
use App\Http\Resources\CommentResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_status_resource_must_have_the_necesary_fields()
    {
        $status = Status::factory()->hasComments(2)->create();

        $statusResource = StatusResource::make($status)->resolve();

        $this->assertEquals($status->id, $statusResource['id']);

        $this->assertEquals($status->body, $statusResource['body']);

        $this->assertEquals($status->created_at->diffForHumans(), $statusResource['ago']);

        $this->assertEquals(false, $statusResource['is_liked']);
        
        $this->assertEquals(0, $statusResource['likes_count']);

        $this->assertEquals(CommentResource::class, $statusResource['comments']->collects);
        
        $this->assertInstanceOf(Comment::class, $statusResource['comments']->first()->resource);

        $this->assertInstanceOf(UserResource::class, $statusResource['user']);
        
        $this->assertInstanceOf(User::class, $statusResource['user']->resource);
    }
}
