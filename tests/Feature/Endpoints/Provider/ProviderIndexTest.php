<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->network = Network::factory()->create();

        $this->createProvider($this->state, $this->provider);
        $this->createProvider();
    }

    /** @test */
    public function it_returns_list_of_providers()
    {
        // arrange
        $provider = $this->provider;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index'));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.label', $provider->label);
    }

    /** @test */
    public function it_returns_providers_filtered_by_state()
    {
        // arrange
        $provider = $this->provider;
        $state    = $this->state;

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.providers.index', [
                'state' => $state->id,
            ]));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
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
                'keywords' => $provider->label,
            ]));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', $provider->label);
    }

    private function createProvider(&$state = null, &$provider = null)
    {
        $state    = State::factory()->create();
        $location = Location::factory()->for($state, 'addressState')->create();
        $provider = Provider::factory()->create();
        $provider->locations()->attach($location);
    }
}
