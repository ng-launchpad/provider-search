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

    protected function setUp(): void
    {
        parent::setUp();

        $this->createProvider($this->state, $this->network, $this->provider);
        $this->createProvider($this->state, $this->network);
        $this->createProvider($this->state);
        $this->createProvider();
    }

    /** @test */
    public function it_requires_a_network_id()
    {
        // arrange
        // assert
        $this->expectException(ValidationException::class);

        // act
        $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'state_id' => $this->state->id,
            ]));
    }

    /** @test */
    public function it_requires_a_state_id()
    {
        // assert
        $this->expectException(ValidationException::class);

        // act
        $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $this->network->id,
            ]));
    }

    /** @test */
    public function it_returns_list_of_providers_for_a_network_and_state()
    {
        // arrange
        $provider = $this->provider;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $this->network->id,
                'state_id'   => $this->state->id,
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
        $provider = $this->provider;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'network_id' => $this->network->id,
                'state_id'   => $this->state->id,
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

    private function createProvider(State &$state = null, Network &$network = null, Provider &$provider = null)
    {
        $state    = $state ?? State::factory()->create();
        $network  = $network ?? Network::factory()->create();
        $location = Location::factory()->for($state, 'addressState')->create();
        $provider = $provider ?? Provider::factory()->for($network)->create();
        $provider->locations()->attach($location);
    }
}
