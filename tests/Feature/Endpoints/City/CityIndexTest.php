<?php

namespace Tests\Feature\Endpoints\City;

use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\State;
use Faker\Factory;
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
        // arrange
        $faker   = Factory::create();
        $network = $this->network;
        $state1  = $this->state;
        $state2  = State::factory()->create();
        $city1   = $faker->unique()->city;
        $city2   = $faker->unique()->city;

        $location1 = Location::factory()->for($state1)->create(['address_city' => $city1]);
        $location2 = Location::factory()->for($state1)->create(['address_city' => $city1]);
        $location3 = Location::factory()->for($state2)->create(['address_city' => $city2]);

        $provider1 = Provider::factory()->for($network)->create();
        $provider1->locations()->attach($location1);
        $provider1->locations()->attach($location2);

        $provider2 = Provider::factory()->for($network)->create();
        $provider2->locations()->attach($location3);

        // act
        $response1 = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.cities.index', [
                'network_id' => $network->id,
                'state_id'   => $state1->id,
            ]));

        $response2 = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.cities.index', [
                'network_id' => $network->id,
                'state_id'   => $state2->id,
            ]));

        // assert
        $response1
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0', $city1);

        $response2
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0', $city2);
    }

    /** @test */
    public function it_returns_list_of_cities_for_version()
    {
        // arrange
        $faker   = Factory::create();
        $network = $this->network;
        $state1  = $this->state;
        $state2  = State::factory()->create();
        $city1   = $faker->unique()->city;
        $city2   = $faker->unique()->city;

        //  Current Version
        $location1 = Location::factory()->for($state1)->create(['address_city' => $city1]);
        $location2 = Location::factory()->for($state1)->create(['address_city' => $city1]);
        $location3 = Location::factory()->for($state2)->create(['address_city' => $city2]);

        $provider1 = Provider::factory()->for($network)->create();
        $provider1->locations()->attach($location1);
        $provider1->locations()->attach($location2);

        $provider2 = Provider::factory()->for($network)->create();
        $provider2->locations()->attach($location3);

        //  Next version
        $locationV2 = Location::factory()->for($state1)->create([
            'version'      => Setting::nextVersion(),
            'address_city' => $city1,
        ]);
        $providerV2 = Provider::factory()->for($network)->create(['version' => Setting::nextVersion()]);

        // act
        $response1 = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.cities.index', [
                'network_id' => $network->id,
                'state_id'   => $state1->id,
            ]));

        $response2 = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.cities.index', [
                'network_id' => $network->id,
                'state_id'   => $state2->id,
            ]));

        // assert
        $response1
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0', $city1);

        $response2
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0', $city2);

        self::assertEquals(4, Location::count());
    }
}
