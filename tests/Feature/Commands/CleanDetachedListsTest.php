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

class CleanDetachedListsTest extends TestCase
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
        $this->hospital = Hospital::factory()->create();
        $this->language = Language::factory()->create();
        $this->speciality = Speciality::factory()->create();
    }

    /** @test */
    public function it_cleans_detached_hospitals()
    {
        // arrange
        $provider = $this->provider;
        $hospital = $this->hospital;
        $provider->hospitals()->attach($hospital);
        $provider->delete();

        // act
        $exitcode = Artisan::call('app:clean-detached-lists');

        // assert
        $this->assertTrue(Hospital::count() == 0);
    }

    /** @test */
    public function it_cleans_detached_languages()
    {
        // arrange
        $provider = $this->provider;
        $language = $this->language;
        $provider->languages()->attach($language);
        $provider->delete();

        // act
        $exitcode = Artisan::call('app:clean-detached-lists');

        // assert
        $this->assertTrue(Language::count() == 0);
    }

    /** @test */
    public function it_cleans_detached_specialities()
    {
        // arrange
        $provider = $this->provider;
        $speciality = $this->speciality;
        $provider->specialities()->attach($speciality);
        $provider->delete();

        // act
        $exitcode = Artisan::call('app:clean-detached-lists');

        // assert
        $this->assertTrue(Speciality::count() == 0);
    }

    /** @test */
    public function it_cleans_detached_locations()
    {
        // arrange
        $provider = $this->provider;
        $location = $this->location;
        $provider->locations()->attach($location);
        $provider->delete();

        // act
        $exitcode = Artisan::call('app:clean-detached-lists');

        // assert
        $this->assertTrue(Location::count() == 0);
    }
}
