<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProviderIndexTest extends TestCase
{
    use RefreshDatabase;

    const CITY = 'TEST_CITY_NAME';

    private $network;
    private $state;
    private $location;
    private $provider;
    private $facility;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createFacility($this->state, $this->network, $this->facility);
        $this->createLocation($this->state, $this->location, self::CITY);
        $this->createProvider($this->state, $this->network, $this->provider, $this->location);
        $this->createProvider($this->state);
        $this->createProvider();
    }

    /** @test */
    public function it_requires_a_network_id()
    {
        // arrange
        $state = $this->state;

        // assert
        $this->expectException(ValidationException::class);

        // act
        $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'state_id' => $state->id,
            ]));
    }

    /** @test */
    public function it_requires_a_state_id()
    {
        // arrange
        $network = $this->network;

        // assert
        $this->expectException(ValidationException::class);

        // act
        $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $network->id,
            ]));
    }

    /** @test */
    public function it_returns_list_of_providers_for_a_network_and_state()
    {
        // arrange
        $network  = $this->network;
        $state    = $this->state;
        $provider = $this->provider;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $network->id,
                'state_id'   => $state->id,
            ]));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.label', $provider->label);
    }

    /** @test */
    public function it_returns_providers_filtered_by_keyword()
    {
        // arrange
        $network  = $this->network;
        $state    = $this->state;
        $provider = $this->provider;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $network->id,
                'state_id'   => $state->id,
                'keywords'   => $provider->label,
            ]));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', $provider->label);
    }

    /** @test */
    public function it_returns_provider_filtered_by_type()
    {
        // arrange
        $network  = $this->network;
        $state    = $this->state;
        $provider = $this->provider;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $network->id,
                'state_id'   => $state->id,
                'type'       => 'provider',
            ]));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', $provider->label);
    }

    /** @test */
    public function it_returns_facility_filtered_by_type()
    {
        // arrange
        $network  = $this->network;
        $state    = $this->state;
        $facility = $this->facility;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $network->id,
                'state_id'   => $state->id,
                'type'       => 'facility',
            ]));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', $facility->label);
    }

    /** @test */
    public function it_returns_providers_filtered_by_scope_city()
    {
        // arrange
        $network  = $this->network;
        $state    = $this->state;
        $provider = $this->provider;
        $keywords = self::CITY;
        $scope    = Provider::SCOPE_CITY;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $network->id,
                'state_id'   => $state->id,
                'keywords'   => $keywords,
                'scope'      => $scope,
            ]));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', $provider->label);
    }

    private function createProvider(
        State &$state = null,
        Network &$network = null,
        Provider &$provider = null,
        Location &$location = null
    ) {
        $state = $state ?? State::factory()->create();

        $network = $network ?? Network::factory()->create();

        $location = $location ?? Location::factory()->for($state)->create();

        $provider = $provider ?? Provider::factory()->for($network)->create();

        $provider->is_facility = false;
        $provider->save();

        $provider->locations()->attach($location);
    }

    private function createFacility(
        State &$state = null,
        Network &$network = null,
        Provider &$provider = null
    ) {
        $this->createProvider($state, $network, $provider);

        $provider->is_facility = true;
        $provider->save();
    }

    private function createLocation(
        State &$state = null,
        Location &$location = null,
        string $city = null
    ) {
        $state    = $state ?? State::factory()->create();
        $location = $location ?? Location::factory()->for($state)->create();

        if ($city) {
            $location->address_city = $city;
            $location->save();
        }
    }
}
