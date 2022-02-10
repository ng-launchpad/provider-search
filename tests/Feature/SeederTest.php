<?php

namespace Tests\Feature;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Models\State;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_seeds_the_database()
    {
        // act
        $this->seed(DatabaseSeeder::class);

        // assert
        $this->assertTrue(Network::count() > 0);
        $this->assertTrue(State::count() > 0);
        $this->assertTrue(Language::count() > 0);
        $this->assertTrue(Speciality::count() > 0);
        $this->assertTrue(Location::count() > 0);
        $this->assertTrue(Hospital::count() > 0);
        $this->assertTrue(Provider::count() > 0);
    }
}
