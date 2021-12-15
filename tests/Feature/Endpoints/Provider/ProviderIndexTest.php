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

    private $network;
    private $state;
    private $provider;
    private $facility;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createProvider($this->state, $this->network, $this->provider);
        $this->createFacility($this->state, $this->network, $this->facility);
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
    public function it_returns_providers_filtered_by_type()
    {
        self::markTestIncomplete();
        //  @todo (Pablo 2021-12-15) - complete this test
    }

    /** @test */
    public function it_returns_providers_filtered_by_scope()
    {
        self::markTestIncomplete();
        //  @todo (Pablo 2021-12-15) - complete this test
    }

    private function createProvider(
        State &$state = null,
        Network &$network = null,
        Provider &$provider = null
    ) {
        $state = $state ?? State::factory()->create();

        $network = $network ?? Network::factory()->create();

        $location = Location::factory()->for($state, 'addressState')->create();

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
}
