<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Returns a User after sign in
     *
     * @param  \App\Models\User|null  $user
     * @return \App\Models\User
     */
    protected function signIn($user = null)
    {
        $user = $user ?: User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    /**
     * Assert Class Uses Trait
     *
     * @param  string  $model
     * @param  string|array  $trait
     * @return void
     */
    protected function assertClassUsesTrait($model, $trait)
    {
        $traits = collect($trait);
        $traitsUsed = class_uses($model);

        foreach ($traits as $trait) {
            $this->assertArrayHasKey($trait, $traitsUsed, "$model must use $trait trait");
        }
    }

}
