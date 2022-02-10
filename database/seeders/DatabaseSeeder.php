<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Run in every environment
        $this->call([
            // Run in every environment
            NetworkSeeder::class,
            StateSeeder::class,
        ]);

        // Run everywhere except production
        if (!App::environment(['production'])) {
            $this->call([
                LanguageSeeder::class,
                SpecialitySeeder::class,
                LocationSeeder::class,
                HospitalSeeder::class,
                ProviderSeeder::class,
            ]);
        }
    }
}
