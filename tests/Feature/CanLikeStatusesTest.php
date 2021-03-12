<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Status;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanLikeStatusesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function guests_users_can_not_like_statuses()
    {
        $status = Status::factory()->create();

        $response = $this->postJson(route('statuses.likes.store', $status));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_like_and_unlike_statuses()
    {
        $user = $this->signIn();
        $status = Status::factory()->create();

        $this->assertCount(0, $status->likes);

        $response = $this->postJson(route('statuses.likes.store', $status));

        $response->assertJsonFragment(['likes_count' => 1]);

        $this->assertCount(1, $status->fresh()->likes);

        $this->assertDatabaseHas('likes', ['user_id' => $user->id]);
        
        $response = $this->deleteJson(route('statuses.likes.destroy', $status));

        $response->assertJsonFragment(['likes_count' => 0]);
        
        $this->assertCount(0, $status->fresh()->likes);
        
        $this->assertDatabaseMissing('likes', ['user_id' => $user->id]);
    }

}
