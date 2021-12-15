<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            LanguageSeeder::class,
            NetworkSeeder::class,
            SpecialitySeeder::class,
            StateSeeder::class,
            LocationSeeder::class,
            HospitalSeeder::class,
            ProviderSeeder::class,
        ]);
    }
}
