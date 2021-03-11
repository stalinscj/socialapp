<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanCreateStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function users_can_create_statuses()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->type('body', 'Mi primer status')
                ->press('#create-status')
                ->waitForText('Mi primer status')
                ->assertSee('Mi primer status')
                ->assertSee($user->name);
        });
    }

    /**
     * @test
     */
    public function users_can_see_statuses_in_real_time()
    {
        $users = User::factory(2)->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($users) {
            $browser1->loginAs($users->first())
                ->visit('/');
            
            $browser2->loginAs($users->last())
                ->visit('/')
                ->type('body', 'Mi primer status')
                ->press('#create-status')
                ->waitForText('Mi primer status')
                ->assertSee('Mi primer status')
                ->assertSee($users->last()->name);

            $browser1->waitForText('Mi primer status')
                ->assertSee('Mi primer status')
                ->assertSee($users->last()->name);
        });
    }
}
