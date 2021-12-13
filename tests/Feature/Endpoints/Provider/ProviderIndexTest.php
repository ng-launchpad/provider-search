<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Models\Network;
use App\Models\Provider;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderIndexTest extends TestCase
{
    use RefreshDatabase;

    private $state1;
    private $state2;
    private $network;
    private $provider1;
    private $provider2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->state1    = State::factory()->create();
        $this->state2    = State::factory()->create();
        $this->network   = Network::factory()->create();
        $this->provider1 = Provider::factory()->for($this->network)->create();
        $this->provider2 = Provider::factory()->for($this->network)->create();
    }

    /** @test */
    public function it_returns_list_of_providers()
    {
        // arrange
        $provider = $this->provider1;

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
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }

    /** @test */
    public function it_returns_providers_filtered_by_keyword()
    {
        // arrange
        $provider = $this->provider1;

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
}
