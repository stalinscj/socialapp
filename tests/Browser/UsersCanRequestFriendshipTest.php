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
    public function senders_can_create_and_delete_friendship_requests()
    {
        $sender    = User::factory()->create();
        $recipient = User::factory()->create();

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visitRoute('users.show', $recipient)
                ->press('@request-friendship')
                ->waitForText('Cancelar Solicitud')
                ->assertSee('Cancelar Solicitud')
                ->visitRoute('users.show', $recipient)
                ->assertSee('Cancelar Solicitud')
                ->press('@request-friendship')
                ->waitForText('Solicitar Amistad')
                ->assertSee('Solicitar Amistad');
        });
    }
}
