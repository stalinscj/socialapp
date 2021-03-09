<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Status;
use App\Models\Comment;
use App\Events\CommentCreatedEvent;
use Illuminate\Support\Facades\Event;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
        Event::fake([CommentCreatedEvent::class]);

        $user = $this->signIn();
        $status = Status::factory()->create();

        $comment = ['body' => 'Mi primer comentario'];

        $response = $this->postJson(route('statuses.comments.store', $status), $comment)
            ->assertSuccessful();

        $response->assertJson([
            'data' => ['body' => $comment['body']]
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id'   => $user->id,
            'status_id' => $status->id,
            'body'      => $comment['body'],
        ]);
    }

    /**
     * @test
     */
    function an_event_is_fired_when_a_comment_is_created()
    {
        Event::fake([CommentCreatedEvent::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $this->signIn();

        $status = Status::factory()->create();

        $comment = ['body' => 'Mi primer comentario'];

        $this->postJson(route('statuses.comments.store', $status), $comment)
            ->assertSuccessful();

        Event::assertDispatched(CommentCreatedEvent::class, function ($commentCredtedEvent) {
            
            $this->assertInstanceOf(ShouldBroadcast::class, $commentCredtedEvent);

            $this->assertInstanceOf(CommentResource::class, $commentCredtedEvent->comment);
            
            $this->assertInstanceOf(Comment::class, $commentCredtedEvent->comment->resource);
            
            $this->assertEquals(Comment::first()->id, $commentCredtedEvent->comment->id);
            
            $this->assertEquals(
                'socket-id', 
                $commentCredtedEvent->socket, 
                'The event '.get_class($commentCredtedEvent).' must call the method "dontBroadcastToCurrentUser" in the constructor.'
            );

            return true;
        });
    }

    /**
     * @test
     */
    function a_comments_requires_a_body()
    {
        $this->signIn();

        $status = Status::factory()->create();

        $response = $this->postJson(route('statuses.comments.store', $status), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors' => ['body']
        ]);
    }

}
