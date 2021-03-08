<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\Friendship;
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

    /**
     * @test
     */
    public function recipients_can_accept_friendship_requests()
    {
        $friendship = Friendship::factory()->create();

        $this->browse(function (Browser $browser) use ($friendship) {
            $browser->loginAs($friendship->recipient)
                ->visitRoute('accept-friendships.index')
                ->assertSee($friendship->sender->name)
                ->press('@accept-friendship')
                ->waitForText('son amigos')
                ->assertSee('son amigos')
                ->visitRoute('accept-friendships.index')
                ->assertSee('son amigos');
        });
    }

    /**
     * @test
     */
    public function recipients_can_deny_friendship_requests()
    {
        $friendship = Friendship::factory()->create();

        $this->browse(function (Browser $browser) use ($friendship) {
            $browser->loginAs($friendship->recipient)
                ->visitRoute('accept-friendships.index')
                ->assertSee($friendship->sender->name)
                ->press('@deny-friendship')
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
                ->visitRoute('accept-friendships.index')
                ->assertSee('Solicitud denegada');
        });
    }
}
