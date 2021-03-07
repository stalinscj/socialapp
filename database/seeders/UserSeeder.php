<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name'       => 'stalin',
            'first_name' => 'Stalin',
            'last_name'  => 'Sánchez',
            'email'      => 'stalin@email.com',
        ]);
    }
}
