<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Helper\PeopleMap;
use App\Models\Hospital;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderSingleTest extends TestCase
{
    use RefreshDatabase;

    protected $facility;
    protected $provider;

    public function setUp(): void
    {
        parent::setUp();

        // create facility provider with network
        $network  = Network::factory()->create();
        $facility = Provider::factory()->facility()->for($network)->create();

        // create location
        $state = State::factory()->create();
        $location = Location::factory()->for($state)->create();
        $facility->locations()->attach($location);

        // create provider in the same location
        $provider = Provider::factory()->for($network)->create();
        $provider->locations()->attach($location);

        // create hospital with facility name and attach provider
        Hospital::factory()->create(['label' => $facility->label])
            ->providers()->attach($provider);

        // attach specialities
        $provider->specialities()->attach(Speciality::factory()->create(['label' => 'Anesthesiology']));
        $provider->specialities()->attach(Speciality::factory()->create(['label' => 'Radiology']));

        // create one more provider
        Provider::factory()->for($network)->create();

        // pass variables to tests
        $this->facility = $facility;
        $this->provider = $provider;
    }

    /** @test */
    public function it_returns_a_provider()
    {
        // arrange
        $facility = $this->facility;
        $provider = $this->provider;

        // act
        $response = $this->withoutExceptionHandling()
            ->getJson(route('api.providers.single', $facility));

        // assert
        $response
            ->assertOk()
            ->assertJsonPath('data.label', $facility->label)
            ->assertJsonPath('data.speciality_groups.0.label', PeopleMap::GROUP_ANESTHESIOLOGISTS)
            ->assertJsonPath('data.speciality_groups.0.people.0.label', $provider->label)
            ->assertJsonPath('data.speciality_groups.1.label', PeopleMap::GROUP_ASSISTANT_SURGEONS);
    }
}
