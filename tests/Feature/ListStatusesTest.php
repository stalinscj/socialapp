<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Status;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ListStatusesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_get_all_statuses()
    {
        Status::factory(3)->create(['created_at' => now()->subDays(1)]);
        
        $lastStatus = Status::factory()->create();

        $response = $this->getJson(route('statuses.index'));

        $response->assertOk();

        $response->assertJson([
            'meta' => ['total' => 4],
        ]);

        $response->assertJsonStructure([
            'data',
            'links' => ['prev', 'next'],
        ]);

        $this->assertEquals($lastStatus->body, $response->json('data.0.body'));
    }

    /**
     * @test
     */
    public function can_get_statuses_for_a_specific_user()
    {
        $user = $this->signIn();
        
        $statuses = Status::factory(2)
            ->for($user)
            ->state(new Sequence(
                ['created_at' => now()->subDay(1)], 
                ['created_at' => now()]
            ))
            ->create();

        Status::factory(5)->create();
        
        $response = $this->getJson(route('users.statuses.index', $user))
            ->assertOk();

        $response->assertJson([
            'meta' => ['total' => 2],
        ]);

        $response->assertJsonStructure([
            'data',
            'links' => ['prev', 'next'],
        ]);

        $this->assertEquals($statuses->last()->body, $response->json('data.0.body'));
    }

    /**
     * @test
     */
    public function can_see_individual_status()
    {
        $status = Status::factory()->create();

        $this->get($status->getPath())
            ->assertViewIs('statuses.show')
            ->assertSee($status->body);
    }

}
