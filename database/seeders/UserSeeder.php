<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    // run seeder
    public function run()
    {
        // seed predefined users
        foreach ($this->getUsers() as $seed) {
            $user = User::create($seed);
        }

        // seed users from factory
        $users = User::factory()
            ->times(9)
            ->past()
            ->create();
    }

    // get predefined users
    public function getUsers()
    {
        return [
            [
                'name' => 'Hello Shedcollective',
                'email' => 'hello@shedcollective.org',
                'password' => Hash::make('secretsecret'),
            ]
        ];
    }
}
