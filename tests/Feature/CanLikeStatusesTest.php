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
    public function an_authenticated_user_can_like_statuses()
    {
        $user = $this->signIn();
        $status = Status::factory()->create();

        $this->postJson(route('statuses.likes.store', $status));

        $this->assertDatabaseHas('likes', [
            'user_id'   => $user->id,
            'status_id' => $status->id
        ]);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_unlike_statuses()
    {
        $user = $this->signIn();
        $status = Status::factory()->create();
        $status->like($user);

        $this->deleteJson(route('statuses.likes.destroy', $status));

        $this->assertDatabaseMissing('likes', [
            'user_id'   => $user->id,
            'status_id' => $status->id
        ]);
    }

}
