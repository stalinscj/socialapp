<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanSeeFriendsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_cannot_access_the_list_of_friends()
    {
        $this->get(route('friends.index'))
            ->assertRedirect('login');
    }

    /**
     * @test
     */
    public function can_see_a_list_of_friends()
    {
        $sender    = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::factory()
            ->for($sender, 'sender')
            ->for($recipient, 'recipient')
            ->accepted()
            ->create();

        $this->signIn($sender);

        $this->get(route('friends.index'))
            ->assertViewIs('friends.index')
            ->assertSee($recipient->name);
        
        $this->signIn($recipient);

        $this->get(route('friends.index'))
            ->assertViewIs('friends.index')
            ->assertSee($sender->name);
    }
}
