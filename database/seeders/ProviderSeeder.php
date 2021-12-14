<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::factory()
            ->times(100)
            ->make()
            ->each(function (Provider $provider) {

                if (rand(0, 1)) {
                    $provider->network()->associate(Network::query()->inRandomOrder()->first());
                }

                $provider->save();

                for ($i = 0; $i < rand(1, 4); $i++) {
                    $provider->locations()->attach(Location::query()->inRandomOrder()->first());
                }

            });
    }
}
