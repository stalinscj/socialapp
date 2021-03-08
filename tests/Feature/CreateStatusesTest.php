<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Status;
use App\Events\StatusCreatedEvent;
use Illuminate\Support\Facades\Event;
use App\Http\Resources\StatusResource;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateStatusesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function guests_users_can_not_create_statuses()
    {
        $response = $this->postJson(route('statuses.store'), ['body' => 'Mi primer status']);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    function an_authenticated_user_can_create_statuses()
    {
        Event::fake([StatusCreatedEvent::class]);

        $user = $this->signIn();

        $attributes = Status::factory()->for($user)->raw();

        $response = $this->postJson(route('statuses.store'), $attributes);

        $response->assertJson([
            'data' => [
                'body' => $attributes['body'],
                'user' => [
                    'name'   => $user->name,
                    'link'   => $user->link(),
                    'avatar' => $user->avatar(),
                ]
            ]
        ]);

        $this->assertDatabaseHas('statuses', $attributes);
    }

    /**
     * @test
     */
    function an_event_is_fired_when_a_status_is_created()
    {
        Event::fake([StatusCreatedEvent::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $this->signIn();

        $attributes = Status::factory()->raw();

        $this->postJson(route('statuses.store'), $attributes);

        Event::assertDispatched(StatusCreatedEvent::class, function ($statusCredtedEvent) {
            
            $this->assertInstanceOf(ShouldBroadcast::class, $statusCredtedEvent);

            $this->assertInstanceOf(StatusResource::class, $statusCredtedEvent->status);
            
            $this->assertInstanceOf(Status::class, $statusCredtedEvent->status->resource);
            
            $this->assertEquals(Status::first()->id, $statusCredtedEvent->status->id);
            
            $this->assertEquals(
                'socket-id', 
                $statusCredtedEvent->socket, 
                'The event '.get_class($statusCredtedEvent).' must call the method "dontBroadcastToCurrentUser" in the constructor.'
            );

            return true;
        });
    }

    /**
     * @test
     */
    function a_status_requires_a_body()
    {
        $this->signIn();

        $response = $this->postJson(route('statuses.store'), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors' => ['body']
        ]);
    }

    /**
     * @test
     */
    function a_status_requires_a_body_a_minimum_length()
    {
        $this->signIn();

        $response = $this->postJson(route('statuses.store'), ['body' => 'abcd']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors' => ['body']
        ]);
    }
}
