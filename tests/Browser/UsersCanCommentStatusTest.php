<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Status;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanCommentStatusTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function guests_cannot_comment_statuses()
    {
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($status) {
            $browser->visit('/')
                ->waitForText($status->body)
                ->press('@comment-btn')
                ->assertPathIs('/login');
        });
    }

    /**
     * @test
     */
    public function users_can_comment_statuses()
    {
        $user   = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $status) {

            $comment = 'Mi primer comentario';

            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->type('comment', $comment)
                ->press('@comment-btn')
                ->waitForText($comment)
                ->assertSee($comment);
        });
    }
}
