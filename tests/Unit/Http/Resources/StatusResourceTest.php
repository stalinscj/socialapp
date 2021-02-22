<?php

namespace Tests\Unit\Http\Resources;

use Tests\TestCase;
use App\Models\Status;
use App\Http\Resources\StatusResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_status_resource_must_have_the_necesary_fields()
    {
        $status = Status::factory()->create();

        $statusResource = StatusResource::make($status)->resolve();

        $this->assertEquals($status->id, $statusResource['id']);

        $this->assertEquals($status->body, $statusResource['body']);

        $this->assertEquals($status->user->name, $statusResource['user_name']);

        $this->assertEquals('img/default-avatar.jpg', $statusResource['user_avatar']);

        $this->assertEquals($status->created_at->diffForHumans(), $statusResource['ago']);

        $this->assertEquals(false, $statusResource['is_liked']);
    }
}
