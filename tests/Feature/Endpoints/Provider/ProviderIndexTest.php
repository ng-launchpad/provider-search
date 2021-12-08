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

    private $stateTexas;
    private $stateCalifornia;
    private $provider1;
    private $provider2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateTexas = State::factory()
            ->create([
                'label' => 'Texas',
                'code'  => 'TX',
            ]);

        $this->stateCalifornia = State::factory()
            ->create([
                'label' => 'California',
                'code'  => 'CA',
            ]);

        $this->provider1 = Provider::factory()
            ->for($this->stateTexas)
            ->create([
                'label' => 'FizzBuzz Inc',
            ]);

        $this->provider2 = Provider::factory()
            ->for($this->stateCalifornia)
            ->create([
                'label' => 'FooBar Ltd.',
            ]);
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
        $state    = $this->stateTexas;

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
}
