<?php

namespace Tests\Browser;

use App\Models\User;
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

    /**
     * @test
     */
    public function users_can_see_their_like_notifications_in_real_time()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user, $status) {
            $browser1->loginAs($status->user)
                ->visit('/');

            $browser2->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->press('@like-btn')
                ->waitForText('TE GUSTA');

            $browser1->waitForTextIn('@notifications-count', 1)
                ->assertSeeIn('@notifications-count', 1);
        });
    }

    /**
     * @test
     */
    public function users_can_see_their_comment_notifications_in_real_time()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user, $status) {
            $browser1->loginAs($status->user)
                ->visit('/');

            $browser2->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->type('comment', 'Mi comentario')
                ->press('@comment-btn');

            $browser1->waitForTextIn('@notifications-count', 1)
                ->assertSeeIn('@notifications-count', 1);
        });
    }

}
