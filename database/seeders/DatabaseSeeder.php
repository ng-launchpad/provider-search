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
            //  Required in prod
            NetworkSeeder::class,
            StateSeeder::class,

            //  Dev-only
            LanguageSeeder::class,
            SpecialitySeeder::class,
            LocationSeeder::class,
            HospitalSeeder::class,
            ProviderSeeder::class,
        ]);
    }
}
