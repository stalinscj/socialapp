<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function guests_users_can_not_create_statuses()
    {
        $response = $this->post(route('statuses.store'), ['body' => 'Mi primer status']);

        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    function an_authenticated_user_can_create_statuses()
    {
        $user = $this->signIn();

        $response = $this->postJson(route('statuses.store'), ['body' => 'Mi primer status']);

        $response->assertJson([
            'body' => 'Mi primer status'
        ]);

        $this->assertDatabaseHas('statuses', [
            'user_id' => $user->id,
            'body'    => 'Mi primer status'
        ]);
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
