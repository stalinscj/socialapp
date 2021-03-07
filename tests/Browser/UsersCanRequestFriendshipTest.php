<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanRequestFriendshipTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function users_can_create_statuses()
    {
        $sender    = User::factory()->create();
        $recipient = User::factory()->create();

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visitRoute('users.show', $recipient)
                ->press('@request-friendship')
                ->waitForText('Solicitud Enviada')
                ->assertSee('Solicitud Enviada');
        });
    }
}
