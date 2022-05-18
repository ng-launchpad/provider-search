<?php

namespace Tests\Unit\DataSource\Mapper;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\Speciality;
use App\Models\State;
use App\Services\DataSource\Mapper\Cigna;
use App\Services\DataSource\Mapper\Vsp;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CignaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_extracts_the_languages()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getLanguageData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();

        $mapper->setVersion(Setting::nextVersion());

        // act
        $mapper
            ->extractLanguages($collection)
            ->unique()
            ->each(fn(Language $model) => $model->save());

        // assert
        $this->assertCount(0, Language::all());
    }

    /** @test */
    public function it_extracts_the_locations()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getLocationData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();

        $mapper->setVersion(Setting::nextVersion());

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum[Cigna::COL_SERVICE_LOCATION_STATE];
            $state->code  = $datum[Cigna::COL_SERVICE_LOCATION_STATE];
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
    public function it_extracts_the_specialities()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getSpecialityData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();

        $mapper->setVersion(Setting::nextVersion());

        // act
        $mapper
            ->extractSpecialities($collection)
            ->unique()
            ->each(fn(Speciality $model) => $model->save());

        // assert
        $this->assertCount(0, Speciality::all());
    }

    /** @test */
    public function it_extracts_the_hospitals()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getHospitalData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();

        $mapper->setVersion(Setting::nextVersion());

        // act
        $mapper
            ->extractHospitals($collection)
            ->unique()
            ->each(fn(Hospital $model) => $model->save());

        // assert
        $this->assertCount(0, Hospital::all());
    }

    /** @test */
    public function it_extracts_the_providers()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getProviderData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        // act
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        // assert
        $this->assertCount(4, Provider::all());
    }

    /** @test */
    public function it_extracts_the_provider_locations()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getProviderLocationData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum[Cigna::COL_SERVICE_LOCATION_STATE];
            $state->code  = $datum[Cigna::COL_SERVICE_LOCATION_STATE];
            $state->save();
        }

        //  Ensure generated Providers exist
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        //  Ensure generated Locations exist
        $mapper
            ->extractLocations($collection)
            ->unique()
            ->each(fn(Location $model) => $model->save());

        // act
        $mapper
            ->extractProviderLocations($collection, $network)
            ->unique()
            ->each(function (array $set) {
                [$provider, $location, $is_primary] = $set;
                $provider
                    ->locations()
                    ->attach(
                        $location,
                        [
                            'is_primary' => $is_primary,
                        ]
                    );
            });

        // assert
        $providers          = Provider::all();
        $provider1Locations = $providers->get(0)->locations();
        $provider2Locations = $providers->get(1)->locations();

        $this->assertCount(2, $providers);
        $this->assertEquals(2, $provider1Locations->count());
        $this->assertTrue($provider1Locations->get()->get(0)->pivot->is_primary);
        $this->assertFalse($provider1Locations->get()->get(1)->pivot->is_primary);

        $this->assertEquals(1, $provider2Locations->count());
        $this->assertTrue($provider2Locations->get()->get(0)->pivot->is_primary);
    }

    /** @test */
    public function it_extracts_the_provider_languages()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getProviderLanguageData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        //  Ensure generated Providers exist
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        // act
        $mapper
            ->extractProviderLanguages($collection, $network)
            ->unique()
            ->each(function (array $set) {
                [$provider, $language] = $set;
                $provider->languages()->attach($language);
            });

        // assert
        $providers          = Provider::all();
        $provider1Languages = $providers->get(0)->languages();
        $provider2Languages = $providers->get(1)->languages();

        $this->assertCount(2, $providers);
        $this->assertEquals(0, $provider1Languages->count());
        $this->assertEquals(0, $provider2Languages->count());
    }

    /** @test */
    public function it_extracts_the_provider_specialities()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getProviderSpecialityData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        //  Ensure generated Providers exist
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        // act
        $mapper
            ->extractProviderSpecialities($collection, $network)
            ->unique()
            ->each(function (array $set) {
                [$provider, $speciality] = $set;
                $provider->specialities()->attach($speciality);
            });

        // assert
        $providers             = Provider::all();
        $provider1Specialities = $providers->get(0)->specialities();
        $provider2Specialities = $providers->get(1)->specialities();

        $this->assertCount(2, $providers);
        $this->assertEquals(0, $provider1Specialities->count());
        $this->assertEquals(0, $provider2Specialities->count());
    }

    /** @test */
    public function it_extracts_the_provider_hospitals()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getProviderHospitalData();
        $collection = new Collection($data);
        $mapper     = Cigna::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        //  Ensure generated Providers exist
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        // act
        $mapper
            ->extractProviderHospitals($collection, $network)
            ->unique()
            ->each(function (array $set) {
                [$provider, $hospital] = $set;
                $provider->hospitals()->attach($hospital);
            });

        // assert
        $providers          = Provider::all();
        $provider1Hospitals = $providers->get(0)->hospitals();
        $provider2Hospitals = $providers->get(1)->hospitals();

        $this->assertCount(2, $providers);
        $this->assertEquals(0, $provider1Hospitals->count());
        $this->assertEquals(0, $provider2Hospitals->count());
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
            Cigna::COL_PHARMACY_NAME                                     => $faker->company,
            Cigna::COL_SERVICE_LOCATION_LINE_1               => $faker->streetAddress,
            Cigna::COL_SERVICE_LOCATION_CITY                 => $faker->city,
            Cigna::COL_SERVICE_LOCATION_STATE                => $faker->stateAbbr,
            Cigna::COL_SERVICE_LOCATION_ZIP_CODE             => $faker->postcode,
            Cigna::COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER => $faker->phoneNumber,
        ];
    }

    private function getLanguageData(): array
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
        $faker = Factory::create();
        return [
            Cigna::COL_PHARMACY_NAME   => $faker->company,
            Cigna::COL_NPI => $faker->unique()->numerify('##########'),
        ];
    }

    private function getSpecialityData(): array
    {
        //  Data source does not contain speciality data
        return [];
    }

    private function getHospitalData(): array
    {
        //  Data source does not contain speciality data
        return [];
    }

    private function getProviderLocationData(): array
    {
        $provider = $this->getProviderDatum();

        return [
            $provider + $this->getLocationDatum(),
            $provider + $this->getLocationDatum(),

            //  Third option which should be attached to a different Provider
            $this->getProviderDatum() + $this->getLocationDatum(),
        ];
    }

    private function getProviderLanguageData(): array
    {
        //  Data source does not contain language data
        return [
            $this->getProviderDatum(),
            $this->getProviderDatum(),
        ];
    }

    private function getProviderSpecialityData(): array
    {
        //  Data source does not contain speciality data
        return [
            $this->getProviderDatum(),
            $this->getProviderDatum(),
        ];
    }

    private function getProviderHospitalData(): array
    {
        //  Data source does not contain hospital data
        return [
            $this->getProviderDatum(),
            $this->getProviderDatum(),
        ];
    }
}
