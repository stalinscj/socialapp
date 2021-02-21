<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Status;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'total' => 4,
        ]);

        $response->assertJsonStructure([
            'data', 'total', 'first_page_url', 'last_page_url',
        ]);

        $this->assertEquals($lastStatus->id, $response->json('data.0.id'));
    }
}
