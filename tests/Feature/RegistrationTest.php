<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function users_can_register()
    {
        $attributes = User::factory()
            ->unverified()
            ->raw([
                'password'       => 'password',
                'remember_token' => null,
            ]);

        $extraAttributes = ['password_confirmation' => 'password'];

        $response = $this->post(route('register'), array_merge($attributes, $extraAttributes));

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', Arr::except($attributes, ['password']));

        $this->assertTrue(Hash::check($attributes['password'], User::first()->password));
    }


}
