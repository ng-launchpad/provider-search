<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\State;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::factory()
            ->times(30)
            ->for(State::findByCodeOrFail('TX'))
            ->make()
            ->each(function (Location $location) {
                $location->hash = $location->hash();
                $location->save();
            });
    }
}
