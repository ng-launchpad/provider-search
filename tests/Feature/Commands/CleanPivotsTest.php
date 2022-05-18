<?php

namespace Tests\Feature\Commands;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CleanPivotsTest extends TestCase
{
    use RefreshDatabase;

    /** @var Provider */
    private $provider;

    /** @var Location */
    private $location;

    /** @var Hospital */
    private $hospital;

    /** @var Language */
    private $language;

    /** @var Speciality */
    private $speciality;

    public function setUp(): void
    {
        parent::setUp();

        Network::factory()->create();
        $this->provider = Provider::factory()->create();

        State::factory()->create();
        $this->location = Location::factory()->create();

        $this->hospital = Hospital::factory()->create();
        $this->language = Language::factory()->create();
        $this->speciality = Speciality::factory()->create();
    }

    /** @test */
    public function it_cleanups_hospital_pivots()
    {
        // arrange
        $provider = $this->provider;
        $hospital = $this->hospital;
        $provider->hospitals()->attach($hospital);
        $provider->hospitals()->attach($hospital);

        // act
        $exitcode = Artisan::call('app:clean-pivots');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue($provider->hospitals()->count() == 1);
    }

    /** @test */
    public function it_cleanups_language_pivots()
    {
        // arrange
        $provider = $this->provider;
        $language = $this->language;
        $provider->languages()->attach($language);
        $provider->languages()->attach($language);

        // act
        $exitcode = Artisan::call('app:clean-pivots');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue($provider->languages()->count() == 1);
    }

    /** @test */
    public function it_cleanups_speciality_pivots()
    {
        // arrange
        $provider = $this->provider;
        $speciality = $this->speciality;
        $provider->specialities()->attach($speciality);
        $provider->specialities()->attach($speciality);

        // act
        $exitcode = Artisan::call('app:clean-pivots');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue($provider->specialities()->count() == 1);
    }

    /** @test */
    public function it_cleanups_location_pivots()
    {
        // arrange
        $provider = $this->provider;
        $location = $this->location;
        $provider->locations()->attach($location);
        $provider->locations()->attach($location);

        // act
        $exitcode = Artisan::call('app:clean-pivots');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue($provider->locations()->count() == 1);
    }

    /** @test */
    public function it_cleanups_primary_location_pivots()
    {
        // arrange
        $provider = $this->provider;
        $location = $this->location;
        $provider->locations()->attach($location);
        $provider->locations()->attach($location, ['is_primary' => true]);
        $provider->locations()->attach($location, ['is_primary' => true]);

        // act
        $exitcode = Artisan::call('app:clean-pivots');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue($provider->locations()->count() == 2);
        $this->assertTrue($provider->primary_locations()->count() == 1);
    }
}
