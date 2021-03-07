<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Status;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanSeeProfilesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function users_can_see_profiles()
    {
        $user = User::factory()
            ->hasStatuses(2)
            ->create();
        
        $otherStatus = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $otherStatus) {
            $browser->visitRoute('users.show', $user)
                ->assertSee($user->name)
                ->waitForText($user->statuses->first()->body)
                ->assertSee($user->statuses->first()->body)
                ->assertSee($user->statuses->last()->body)
                ->assertDontSee($otherStatus->body);
        });
    }
}
