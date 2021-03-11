<?php

namespace Tests\Browser;

use App\Models\Status;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Database\Factories\DatabaseNotificationFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanGetTheirNotificationsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function users_can_manage_their_notifications_in_the_navbar()
    {
       $status = Status::factory()->create();

       $notification = DatabaseNotificationFactory::new()->for($status->user, 'notifiable')
            ->create([
                'data' => [
                    'link'    => route('statuses.show', $status),
                    'message' => 'Haz recibido una notificación',
                ]
            ]);

       $this->browse(function (Browser $browser) use ($status, $notification) {
           $browser->loginAs($status->user)
               ->visit('/')
               ->click("@notifications")
               ->assertSee('Haz recibido una notificación')
               ->click("@{$notification->id}")
               ->assertUrlIs($status->getPath())

               ->click("@notifications")
               ->waitFor("@mark-as-read-{$notification->id}")
               ->press("@mark-as-read-{$notification->id}")
               ->waitFor("@mark-as-unread-{$notification->id}")
               ->assertMissing("@mark-as-read-{$notification->id}")

               ->press("@mark-as-unread-{$notification->id}")
               ->waitFor("@mark-as-read-{$notification->id}")
               ->assertMissing("@mark-as-unread-{$notification->id}");
       });
    }
}
