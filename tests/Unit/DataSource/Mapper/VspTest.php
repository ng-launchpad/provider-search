<?php

namespace Tests\Unit\DataSource\Mapper;

use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Models\State;
use App\Services\DataSource\Mapper\Vsp;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class VspTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_extracts_the_languages()
    {
        // arrange
        $data       = $this->getLanguageData();
        $collection = new Collection($data);
        $mapper     = Vsp::factory();

        // act
        $mapper
            ->extractLanguages($collection)
            ->unique()
            ->each(fn(Language $model) => $model->save());

        // assert
        $this->assertCount(6, Language::all());
    }

    /** @test */
    public function it_extracts_the_locations()
    {
        // arrange
        $data       = $this->getLocationData();
        $collection = new Collection($data);
        $mapper     = Vsp::factory();

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum['ST'];
            $state->code  = $datum['ST'];
            $state->save();
        }

        // act
        $mapper
            ->extractLocations($collection)
            ->unique()
            ->each(fn(Location $model) => $model->save());

        // assert
        $this->assertCount(2, Location::all());
    }

    /** @test */
    public function it_extracts_the_networks()
    {
        // arrange
        $data       = $this->getNetworkData();
        $collection = new Collection($data);
        $mapper     = Vsp::factory();

        // act
        $mapper
            ->extractNetworks($collection)
            ->unique()
            ->each(fn(Network $model) => $model->save());

        // assert
        $this->assertCount(2, Network::all());
    }

    /** @test */
    public function it_extracts_the_specialities()
    {
        // arrange
        $data       = $this->getSpecialityData();
        $collection = new Collection($data);
        $mapper     = Vsp::factory();

        // act
        $mapper
            ->extractSpecialities($collection)
            ->unique()
            ->each(fn(Speciality $model) => $model->save());

        // assert
        $this->assertCount(0, Speciality::all());
    }

    /** @test */
    public function it_extracts_the_providers()
    {
        // arrange
        $data       = $this->getProviderData();
        $collection = new Collection($data);
        $mapper     = Vsp::factory();

        //  Ensure generated Networks exist
        $mapper
            ->extractNetworks($collection)
            ->unique()
            ->each(fn(Network $model) => $model->save());

        // act
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(fn(Provider $model) => $model->save());

        // assert
        $this->assertCount(4, Provider::all());
    }

    /** @test */
    public function it_extracts_the_provider_locations()
    {
        // arrange
        $data       = $this->getProviderLocationData();
        $collection = new Collection($data);
        $mapper     = Vsp::factory();

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum['ST'];
            $state->code  = $datum['ST'];
            $state->save();
        }

        //  Ensure generated Networks exist
        $mapper
            ->extractNetworks($collection)
            ->unique()
            ->each(fn(Network $model) => $model->save());

        //  Ensure generated Providers exist
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(fn(Provider $model) => $model->save());

        //  Ensure generated Locations exist
        $mapper
            ->extractLocations($collection)
            ->unique()
            ->each(fn(Location $model) => $model->save());

        // act
        $mapper
            ->extractProviderLocations($collection)
            ->unique()
            ->each(function (array $set) {
                [$provider, $location, $is_primary] = $set;
                $provider->locations()->attach($location, ['is_primary' => $is_primary]);
            });

        // assert
        $providers          = Provider::all();
        $provider1Locations = $providers->get(0)->locations();
        $provider2Locations = $providers->get(1)->locations();

        $this->assertCount(2, $providers);
        $this->assertEquals(2, $provider1Locations->count());
        $this->assertTrue(
            $provider1Locations->get()->get(0)->pivot->is_primary
        );
        $this->assertFalse(
            $provider1Locations->get()->get(1)->pivot->is_primary
        );

        $this->assertEquals(1, $provider2Locations->count());
        $this->assertTrue(
            $provider2Locations->get()->get(0)->pivot->is_primary
        );
    }

    private function getLocationData(): array
    {
        return [
            $this->getLocationDatum(),
            $this->getLocationDatum(),
        ];
    }

    private function getLocationDatum(): array
    {
        $faker = Factory::create();

        return [
            'PRACTICE NAME'  => $faker->company(),
            'OFFICE ADDRESS' => $faker->streetAddress,
            'OFFICE CITY'    => $faker->city,
            'ST'             => $faker->stateAbbr,
            'ZIP9'           => $faker->postcode,
            'OFFICE PHONE'   => $faker->phoneNumber,
            'COUNTY'         => 'County ' . ucfirst($faker->word),
        ];
    }

    private function getLanguageData(): array
    {
        return [
            [
                'OFFICE LANG 1'     => 'English',
                'OFFICE LANG 2'     => 'Spanish',
                'OFFICE LANG 3'     => '',
                'OFFICE LANG 4'     => '',
                'LANGUAGE SPOKEN 1' => '',
                'LANGUAGE SPOKEN 2' => 'Spanish',
                'LANGUAGE SPOKEN 3' => 'French',
                'LANGUAGE SPOKEN 4' => '',
            ],
            [
                'OFFICE LANG 1'     => '',
                'OFFICE LANG 2'     => 'Spanish',
                'OFFICE LANG 3'     => 'German',
                'OFFICE LANG 4'     => 'French',
                'LANGUAGE SPOKEN 1' => '',
                'LANGUAGE SPOKEN 2' => 'Vietnamese',
                'LANGUAGE SPOKEN 3' => '',
                'LANGUAGE SPOKEN 4' => 'Chinese',
            ],
        ];
    }

    private function getNetworkData(): array
    {
        return [
            $this->getNetworkDatum(),
            $this->getNetworkDatum(),
        ];
    }

    private function getNetworkDatum(): array
    {
        $faker = Factory::create();
        return [
            'PROVIDER NETWORK' => $faker->unique()->words(3, true),
        ];
    }

    private function getSpecialityData(): array
    {
        //  Data source does not contain speciality data
        return [];
    }

    private function getProviderData(): array
    {
        return [
            $this->getProviderDatum(),
            $this->getProviderDatum(),
            $this->getProviderDatum(),
            $this->getProviderDatum(),
        ];
    }

    private function getProviderDatum(): array
    {
        $faker  = Factory::create();
        $gender = $faker->optional()->randomElement(['MALE', 'FEMALE']);
        return [
            'DOCTOR LAST NAME'  => $faker->lastName,
            'DOCTOR FIRST NAME' => $faker->firstName($gender),
            'NPI'               => $faker->unique()->numerify('##########'),
            'DEGREE'            => $faker->randomElement(['MD', 'DO', 'OD']),
            'GENDER'            => $gender,
            'PROVIDER NETWORK'  => $faker->company,
        ];
    }

    private function getProviderLocationData(): array
    {
        $provider = $this->getProviderDatum();
        $network  = $this->getNetworkDatum();

        return [
            array_merge($provider, $this->getLocationDatum(), $network),
            array_merge($provider, $this->getLocationDatum(), $network),

            //  Third option which should be attached to a different Provider
            array_merge($this->getProviderDatum(), $this->getLocationDatum(), $this->getNetworkDatum()),
        ];
    }
}
