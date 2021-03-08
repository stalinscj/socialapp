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
    public function guests_cannot_create_friendship_requests()
    {
        $recipient = User::factory()->create();

        $this->browse(function (Browser $browser) use ($recipient) {
            $browser->visitRoute('users.show', $recipient)
                ->press('@request-friendship')
                ->assertRouteIs('login');
        });
    }

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
                ->waitForText('Cancelar solicitud')
                ->assertSee('Cancelar solicitud')
                ->visitRoute('users.show', $recipient)
                ->assertSee('Cancelar solicitud')
                ->press('@request-friendship')
                ->waitForText('Solicitar amistad')
                ->assertSee('Solicitar amistad');
        });
    }

    /**
     * @test
     */
    public function a_sender_cannot_send_friendship_request_to_itself()
    {
        $sender = User::factory()->create();

        $this->browse(function (Browser $browser) use ($sender) {
            $browser->loginAs($sender)
                ->visitRoute('users.show', $sender)
                ->assertMissing('@request-friendship')
                ->assertSee('Eres tú');
        });
    }

    /**
     * @test
     */
    public function senders_can_delete_accepted_friendship_requests()
    {
        $friendship = Friendship::factory()->accepted()->create();

        $this->browse(function (Browser $browser) use ($friendship) {
            $browser->loginAs($friendship->sender)
                ->visitRoute('users.show', $friendship->recipient)
                ->assertSee('Eliminar de mis amigos')
                ->press('@request-friendship')
                ->waitForText('Solicitar amistad')
                ->assertSee('Solicitar amistad')
                ->visitRoute('users.show', $friendship->recipient)
                ->assertSee('Solicitar amistad');
        });
    }

    /**
     * @test
     */
    public function senders_cannot_delete_denied_friendship_requests()
    {
        $friendship = Friendship::factory()->denied()->create();

        $this->browse(function (Browser $browser) use ($friendship) {
            $browser->loginAs($friendship->sender)
                ->visitRoute('users.show', $friendship->recipient)
                ->assertSee('Solicitud denegada')
                ->press('@request-friendship')
                ->waitForText('Solicitud denegada')
                ->assertSee('Solicitud denegada')
                ->visitRoute('users.show', $friendship->recipient)
                ->assertSee('Solicitud denegada');
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

    /**
     * @test
     */
    public function recipients_can_delete_friendship_requests()
    {
        $friendship = Friendship::factory()->create();

        $this->browse(function (Browser $browser) use ($friendship) {
            $browser->loginAs($friendship->recipient)
                ->visitRoute('accept-friendships.index')
                ->assertSee($friendship->sender->name)
                ->press('@delete-friendship')
                ->waitForText('Solicitud eliminada')
                ->assertSee('Solicitud eliminada')
                ->visitRoute('accept-friendships.index')
                ->assertDontSee('Solicitud eliminada')
                ->assertDontSee($friendship->sender->name);
        });
    }
}
