<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Comment;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanLikeCommentsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function guests_cannot_like_comments()
    {
        $comment = Comment::factory()->create();

        $this->browse(function (Browser $browser) use ($comment) {
            $browser->visit('/')
                ->waitForText($comment->body)
                ->press('@comment-like-btn')
                ->assertPathIs('/login');
        });
    }

    /**
     * @test
     */
    public function users_can_like_and_unlike_comments()
    {
        $user   = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $comment) {
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count', 0)
                ->press('@comment-like-btn')
                ->waitForText('TE GUSTA')
                ->assertSee('TE GUSTA')
                ->assertSeeIn('@comment-likes-count', 1)
                ->press('@comment-like-btn')
                ->waitForText('ME GUSTA')
                ->assertSee('ME GUSTA')
                ->assertSeeIn('@comment-likes-count', 0);
        });
    }

    /**
     * @test
     */
    public function users_can_see_likes_and_unlikes_on_comments_in_real_time()
    {
        $user   = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user, $comment) {
            
            $browser1->visit('/');
            
            $browser2->loginAs($user)
                ->visit('/')
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count', 0)
                ->press('@comment-like-btn')
                ->waitForText('TE GUSTA');

            $browser1->waitForTextIn('@comment-likes-count', 1)
                ->assertSeeIn('@comment-likes-count', 1);

            $browser2->press('@comment-like-btn')
                ->waitForText('ME GUSTA');

            $browser1->waitForTextIn('@comment-likes-count', 0)
                ->assertSeeIn('@comment-likes-count', 0);

        });
    }
}
