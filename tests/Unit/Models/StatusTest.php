<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Like;
use App\Models\User;
use App\Models\Status;
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
    public function a_status_has_many_likes()
    {
        $status = Status::factory()->create();

        Like::factory()->create(['status_id' => $status->id]);

        $this->assertInstanceOf(Like::class, $status->likes->first());
    }

    /**
     * @test
     */
    public function a_status_can_be_liked_and_unliked()
    {
        $user = $this->signIn();
        $status = Status::factory()->create();

        $status->like($user);

        $this->assertEquals(1, $status->likes->count());
        
        $status->unlike($user);

        $this->assertEquals(0, $status->fresh()->likes->count());
    }

    /**
     * @test
     */
    public function a_status_can_be_liked_once()
    {
        $user = $this->signIn();
        $status = Status::factory()->create();

        $status->like($user);
        
        $this->assertEquals(1, $status->likes->count());
        
        $status->like($user);
        
        $this->assertEquals(1, $status->fresh()->likes->count());
    }

    /**
     * @test
     */
    public function a_status_knows_if_has_been_liked()
    {
        $user = $this->signIn();
        $status = Status::factory()->create();

        $this->assertFalse($status->isLiked($user));
        
        $status->like($user);

        $this->assertTrue($status->isLiked($user));
    }
}
