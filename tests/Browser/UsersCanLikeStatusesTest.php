<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Status;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanLikeStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function guests_cannot_like_statuses()
    {
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($status) {
            $browser->visit('/')
                ->waitForText($status->body)
                ->press('@like-btn')
                ->assertPathIs('/login');
        });
    }

    /**
     * @test
     */
    public function users_can_like_and_unlike_statuses()
    {
        $user   = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $status) {
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->assertSeeIn('@likes-count', 0)
                ->press('@like-btn')
                ->waitForText('TE GUSTA')
                ->assertSee('TE GUSTA')
                ->assertSeeIn('@likes-count', 1)
                ->press('@unlike-btn')
                ->waitForText('ME GUSTA')
                ->assertSee('ME GUSTA')
                ->assertSeeIn('@likes-count', 0);
        });
    }
}
