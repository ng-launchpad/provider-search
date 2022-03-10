<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderSingleTest extends TestCase
{
    use RefreshDatabase;

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
            ->assertJsonPath('data.people.0.label', $provider->label);
    }
}
