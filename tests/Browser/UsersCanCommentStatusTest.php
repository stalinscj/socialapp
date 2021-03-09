<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanCommentStatusTest extends DuskTestCase
{
    use DatabaseMigrations;

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

    /**
     * @test
     */
    public function users_can_see_all_comments()
    {
        $comments = Comment::factory(2)->create();

        $this->browse(function (Browser $browser) use ($comments) {
            $browser->visit('/')
                ->waitForText($comments->first()->status->body);

            foreach ($comments as $comment) {
                $browser->assertSee($comment->body)
                    ->assertSee($comment->user->name);
            }
        });
    }

    /**
     * @test
     */
    public function users_can_see_comments_in_real_time()
    {
        $user   = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user, $status) {

            $browser1->visit('/');
            
            $comment = 'Mi primer comentario';

            $browser2->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->type('comment', $comment)
                ->press('@comment-btn')
                ->waitForText($comment)
                ->assertSee($comment);
            
            $browser1->waitForText($comment)
                ->assertSee($comment);
        });
    }
}
