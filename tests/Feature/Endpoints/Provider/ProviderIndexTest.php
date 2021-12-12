<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Models\Provider;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProviderIndexTest extends TestCase
{
    use RefreshDatabase;

    private $state1;
    private $state2;
    private $provider1;
    private $provider2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->state1    = State::factory()->create();
        $this->state2    = State::factory()->create();
        $this->provider1 = Provider::factory()->for($this->state1)->create();
        $this->provider2 = Provider::factory()->for($this->state2)->create();
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
        // arrange
        $provider = $this->provider1;
        $state    = $this->state1;

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
            ->assertJsonPath('data.0.label', $provider->label)
            ->assertJsonPath('data.0.state.code', $state->code);
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
