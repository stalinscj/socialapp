<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

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
}
