<?php

namespace Tests\Feature\Endpoints\Speciality;

use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\Speciality;
use App\Models\State;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SpecialityIndexTest extends TestCase
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
            ->getJson(route('api.specialities.index', [
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
            ->getJson(route('api.specialities.index', [
                'network_id' => $network->id,
            ]));
    }

    /** @test */
    public function it_returns_list_of_specialities()
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

        $speciality1 = Speciality::factory()->create();
        $speciality2 = Speciality::factory()->create();

        $provider1 = Provider::factory()->for($network)->create();
        $provider1->locations()->attach($location1);
        $provider1->locations()->attach($location2);
        $provider1->specialities()->attach($speciality1);

        $provider2 = Provider::factory()->for($network)->create();
        $provider2->locations()->attach($location3);
        $provider2->specialities()->attach($speciality2);

        // act
        $response1 = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.specialities.index', [
                'network_id' => $network->id,
                'state_id'   => $state1->id,
            ]));

        $response2 = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.specialities.index', [
                'network_id' => $network->id,
                'state_id'   => $state2->id,
            ]));

        // assert
        $response1
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0', $speciality1->label);

        $response2
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0', $speciality2->label);
    }

    /** @test */
    public function it_returns_list_of_specialities_for_version()
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

        $speciality1 = Speciality::factory()->create();
        $speciality2 = Speciality::factory()->create();

        $provider1 = Provider::factory()->for($network)->create();
        $provider1->locations()->attach($location1);
        $provider1->locations()->attach($location2);
        $provider1->specialities()->attach($speciality1);

        $provider2 = Provider::factory()->for($network)->create();
        $provider2->locations()->attach($location3);
        $provider2->specialities()->attach($speciality2);

        // Next version
        $locationV2   = Location::factory()->for($state1)->create([
            'version'      => Setting::nextVersion(),
            'address_city' => $city1,
        ]);
        $specialityV2 = Speciality::factory()->create(['version' => Setting::nextVersion()]);
        $providerV2   = Provider::factory()->for($network)->create(['version' => Setting::nextVersion()]);
        $providerV2->locations()->attach($locationV2);
        $providerV2->specialities()->attach($specialityV2);

        // act
        $response1 = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.specialities.index', [
                'network_id' => $network->id,
                'state_id'   => $state1->id,
            ]));

        $response2 = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.specialities.index', [
                'network_id' => $network->id,
                'state_id'   => $state2->id,
            ]));

        // assert
        $response1
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0', $speciality1->label);

        $response2
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0', $speciality2->label);

        self::assertEquals(3, Provider::count());
    }
}
