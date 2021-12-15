<?php

namespace Tests\Feature\Endpoints\City;

use App\Models\Network;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CityIndexTest extends TestCase
{
    use RefreshDatabase;

    private $network;
    private $state;

    protected function setUp(): void
    {
        parent::setUp();

        $this->network = Network::factory()->create();
        $this->state   = State::factory()->create();
    }

    /** @test */
    public function it_requires_network_id()
    {
        // arrange
        $state = $this->state;

        // assert
        $this->expectException(ValidationException::class);

        // act
        $this
            ->withoutExceptionHandling()
            ->getJson(route('api.cities.index', [
                'state_id' => $state->id,
            ]));
    }

    /** @test */
    public function it_requires_state_id()
    {
        // arrange
        $network = $this->network;

        // assert
        $this->expectException(ValidationException::class);

        // act
        $this
            ->withoutExceptionHandling()
            ->getJson(route('api.cities.index', [
                'network_id' => $network->id,
            ]));
    }

    /** @test */
    public function it_returns_list_of_cities()
    {
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }
}
