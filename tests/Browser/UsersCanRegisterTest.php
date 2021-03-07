<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanRegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function user_can_register()
    {
        $user = User::factory()->raw();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visitRoute('register')
                ->type('name',                  $user['name'])
                ->type('first_name',            $user['first_name'])
                ->type('last_name',             $user['last_name'])
                ->type('email',                 $user['email'])
                ->type('password',              $user['password'])
                ->type('password_confirmation', $user['password'])
                ->press('@register-btn')
                ->assertPathIs('/')
                ->assertAuthenticated();
        });
    }

    /**
     * @test
     */
    public function user_cannot_register_with_invalid_information()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('register')
                ->type('name', '')
                ->press('@register-btn')
                ->assertRouteIs('register')
                ->assertPresent('@validation-errors');
        });
    }
}
