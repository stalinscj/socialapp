<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Status;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function guests_users_cannot_commnet_statuses()
    {
        $status = Status::factory()->create();

        $response = $this->postJson(route('statuses.comments.store', $status));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_comment_statuses()
    {
        $user = $this->signIn();
        $status = Status::factory()->create();

        $comment = ['body' => 'Mi primer comentario'];

        $response = $this->postJson(route('statuses.comments.store', $status), $comment);

        $response->assertJson([
            'data' => ['body' => $comment['body']]
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id'   => $user->id,
            'status_id' => $status->id,
            'body'      => $comment['body'],
        ]);
    }

}
