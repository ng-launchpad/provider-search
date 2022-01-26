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
use App\Services\DataSource\Mapper\Aenta;
use Faker\Factory;
use Hamcrest\Core\Set;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AentaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_extracts_the_languages()
    {
        // arrange
        $data          = $this->getLanguageData();
        $expectedLangs = $this->getGeneratedLanguagesFromData($data);
        $collection    = new Collection($data);
        $mapper        = Aenta::factory();

        // act
        $mapper
            ->extractLanguages($collection)
            ->unique()
            ->each(fn(Language $model) => $model->save());

        // assert
        $this->assertCount(count($expectedLangs), Language::all());
    }

    /** @test */
    public function it_extracts_the_locations()
    {
        // arrange
        $data       = $this->getLocationData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum[Aenta::COL_SERVICE_LOCATION_STATE];
            $state->code  = $datum[Aenta::COL_SERVICE_LOCATION_STATE];
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
        // arrange
        $data       = $this->getSpecialityData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();

        // act
        $mapper
            ->extractSpecialities($collection)
            ->unique()
            ->each(fn(Speciality $model) => $model->save());

        // assert
        $this->assertCount(3, Speciality::all());
    }

    /** @test */
    public function it_extracts_the_hospitals()
    {
        // arrange
        $data       = $this->getHospitalData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();

        // act
        $mapper
            ->extractHospitals($collection)
            ->unique()
            ->each(fn(Hospital $model) => $model->save());

        // assert
        $this->assertCount(5, Hospital::all());
    }

    /** @test */
    public function it_extracts_the_providers()
    {
        // arrange
        $data       = $this->getProviderData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();
        $network    = Network::factory()->create();

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
        // arrange
        $data       = $this->getProviderLocationData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();
        $network    = Network::factory()->create();

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum[Aenta::COL_SERVICE_LOCATION_STATE];
            $state->code  = $datum[Aenta::COL_SERVICE_LOCATION_STATE];
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
                $provider->locations()->attach($location, ['is_primary' => $is_primary]);
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
        // arrange
        $data       = $this->getProviderLanguageData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();
        $network    = Network::factory()->create();

        //  Calculate the expected languages
        $expectedLangs = array_map(function ($item) {
            return $this->getGeneratedLanguagesFromData([$item], [
                Aenta::COL_FOREIGN_LANGUAGE1,
                Aenta::COL_FOREIGN_LANGUAGE2,
                Aenta::COL_FOREIGN_LANGUAGE3,
                Aenta::COL_FOREIGN_LANGUAGE4,
                Aenta::COL_FOREIGN_LANGUAGE5,
            ]);
        }, $data);

        //  Ensure generated Providers exist
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        //  Ensure generated languages exist
        foreach ($expectedLangs as $expectedLang) {
            foreach ($expectedLang as $item) {
                Language::create([
                    'version' => Setting::version(),
                    'label'   => $item,
                ]);
            }
        }

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
        $this->assertEquals(count($expectedLangs[0]), $provider1Languages->count());
        $this->assertEquals(count($expectedLangs[1]), $provider2Languages->count());
    }

    /** @test */
    public function it_extracts_the_provider_specialities()
    {
        // arrange
        $data       = $this->getProviderSpecialityData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();
        $network    = Network::factory()->create();

        //  Calculate the expected specialities
        $expectedSpecialities = array_map(function ($item) {
            return $this->getGeneratedSpecialitiesFromData([$item], [
                Aenta::COL_PRIMARY_PROVIDER_SPECIALTY,
                Aenta::COL_SPECIALTY2,
                Aenta::COL_SPECIALTY3,
            ]);
        }, $data);

        //  Ensure generated Providers exist
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        //  Ensure generated specialities exist
        foreach ($expectedSpecialities as $expectedSpeciality) {
            foreach ($expectedSpeciality as $item) {
                Speciality::create([
                    'version' => Setting::version(),
                    'label'   => $item,
                ]);
            }
        }

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
        $this->assertEquals(count($expectedSpecialities[0]), $provider1Specialities->count());
        $this->assertEquals(count($expectedSpecialities[1]), $provider2Specialities->count());
    }

    /** @test */
    public function it_extracts_the_provider_hospitals()
    {
        // arrange
        $data       = $this->getProviderHospitalData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();
        $network    = Network::factory()->create();

        //  Calculate the expected hospitals
        $expectedHospitals = array_map(function ($item) {
            return $this->getGeneratedHospitalsFromData([$item], [
                Aenta::COL_HOSPITAL_AFFILIATION_1,
                Aenta::COL_HOSPITAL_AFFILIATION_2,
                Aenta::COL_HOSPITAL_AFFILIATION_3,
                Aenta::COL_HOSPITAL_AFFILIATION_4,
                Aenta::COL_HOSPITAL_AFFILIATION_5,
            ]);
        }, $data);

        //  Ensure generated Providers exist
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        //  Ensure generated hospitals exist
        foreach ($expectedHospitals as $expectedHospital) {
            foreach ($expectedHospital as $item) {
                Hospital::create([
                    'version' => Setting::version(),
                    'label'   => $item,
                ]);
            }
        }

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
        $this->assertEquals(count($expectedHospitals[0]), $provider1Hospitals->count());
        $this->assertEquals(count($expectedHospitals[1]), $provider2Hospitals->count());
    }

    private function getLanguageData(): array
    {
        return [
            $this->getLanguageDatum(),
            $this->getLanguageDatum(),
        ];
    }

    private function getLanguageDatum(): array
    {
        $faker     = Factory::create();
        $languages = ['English', 'Spanish', 'French', 'German', 'Vietnamese', 'Chinese'];
        return [
            Aenta::COL_FOREIGN_LANGUAGE1 => $faker->optional()->randomElement($languages),
            Aenta::COL_FOREIGN_LANGUAGE2 => $faker->optional()->randomElement($languages),
            Aenta::COL_FOREIGN_LANGUAGE3 => $faker->optional()->randomElement($languages),
            Aenta::COL_FOREIGN_LANGUAGE4 => $faker->optional()->randomElement($languages),
            Aenta::COL_FOREIGN_LANGUAGE5 => $faker->optional()->randomElement($languages),
        ];
    }

    private function getGeneratedLanguagesFromData(array $data, array $keys = null): array
    {
        if (!empty($keys)) {
            $data = array_map(
                fn($item) => array_intersect_key($item, array_combine($keys, $keys)),
                $data
            );
        }

        $expectedLangs = array_map(fn($datum) => array_map(fn($lang) => $lang ?: null, $datum), $data);
        $expectedLangs = array_map(fn($datum) => implode(',', $datum), $expectedLangs);
        $expectedLangs = implode(',', $expectedLangs);
        $expectedLangs = explode(',', $expectedLangs);
        $expectedLangs = array_unique($expectedLangs);
        $expectedLangs = array_filter($expectedLangs);
        $expectedLangs = array_filter($expectedLangs, fn($lang) => $lang !== 'English');

        return $expectedLangs;
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
            Aenta::COL_TIN_OWNER_NAME                        => $faker->company,
            Aenta::COL_SERVICE_LOCATION_LINE_1               => $faker->streetAddress,
            Aenta::COL_SERVICE_LOCATION_CITY                 => $faker->city,
            Aenta::COL_SERVICE_LOCATION_STATE                => $faker->stateAbbr,
            Aenta::COL_SERVICE_LOCATION_ZIP_CODE             => $faker->postcode,
            Aenta::COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER => $faker->phoneNumber,
        ];
    }

    private function getSpecialityData(): array
    {
        return [
            $this->getSpecialityDatum(),
        ];
    }

    private function getSpecialityDatum(): array
    {
        $faker        = Factory::create();
        $specialities = [
            'Orthopedics',
            'Cardiology',
            'Oncology',
            'Internal Medicine',
        ];

        return [
            Aenta::COL_PRIMARY_PROVIDER_SPECIALTY => $faker->unique()->randomElement($specialities),
            Aenta::COL_SPECIALTY2                 => $faker->unique()->randomElement($specialities),
            Aenta::COL_SPECIALTY3                 => $faker->unique()->randomElement($specialities),
        ];
    }

    private function getHospitalData(): array
    {
        return [
            $this->getHospitalDatum(),
        ];
    }

    private function getHospitalDatum(): array
    {
        $faker = Factory::create();

        return [
            Aenta::COL_HOSPITAL_AFFILIATION_1 => $faker->unique()->company,
            Aenta::COL_HOSPITAL_AFFILIATION_2 => $faker->unique()->company,
            Aenta::COL_HOSPITAL_AFFILIATION_3 => $faker->unique()->company,
            Aenta::COL_HOSPITAL_AFFILIATION_4 => $faker->unique()->company,
            Aenta::COL_HOSPITAL_AFFILIATION_5 => $faker->unique()->company,
        ];
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
        $faker      = Factory::create();
        $gender     = $faker->optional()->randomElement(['MALE', 'FEMALE']);
        $isFacility = $faker->boolean;

        return [
            Aenta::COL_PROVIDER_FIRST_NAME        => $isFacility ? '' : $faker->name($gender),
            Aenta::COL_PROVIDER_LAST_NAME         => $isFacility ? $faker->company : $faker->lastName,
            Aenta::COL_PROVIDER_DEGREE            => $faker->randomElement(['MD', 'DO', 'OD']),
            Aenta::COL_PROVIDER_TYPE              => $faker->word,
            Aenta::COL_PROVIDER_GENDER            => $gender ? substr($gender, 0, 1) : '',
            Aenta::COL_ACCEPTING_NEW_PATIENTS_IND => $faker->boolean,
            Aenta::COL_NPI                        => $faker->unique()->numerify('##########'),
            Aenta::COL_INFORMATION_TYPE_CODE      => $isFacility ? 'N' : 'I',
        ];
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
        $provider = $this->getProviderDatum();

        return [
            $provider + $this->getLanguageDatum(),
            $this->getProviderDatum() + $this->getLanguageDatum(),
        ];
    }

    private function getProviderSpecialityData(): array
    {
        $provider = $this->getProviderDatum();

        return [
            $provider + $this->getSpecialityDatum(),
            $this->getProviderDatum() + $this->getSpecialityDatum(),
        ];
    }

    private function getGeneratedSpecialitiesFromData(array $data, array $keys = null): array
    {
        if (!empty($keys)) {
            $data = array_map(
                fn($item) => array_intersect_key($item, array_combine($keys, $keys)),
                $data
            );
        }

        $expectedSpecialities = array_map(fn($datum) => array_map(fn($speciality) => $speciality ?: null, $datum), $data);
        $expectedSpecialities = array_map(fn($datum) => implode('|', $datum), $expectedSpecialities);
        $expectedSpecialities = implode('|', $expectedSpecialities);
        $expectedSpecialities = explode('|', $expectedSpecialities);
        $expectedSpecialities = array_unique($expectedSpecialities);
        $expectedSpecialities = array_filter($expectedSpecialities);

        return $expectedSpecialities;
    }

    private function getProviderHospitalData(): array
    {
        $provider = $this->getProviderDatum();

        return [
            $provider + $this->getHospitalDatum(),
            $this->getProviderDatum() + $this->getHospitalDatum(),
        ];
    }

    private function getGeneratedHospitalsFromData(array $data, array $keys = null): array
    {
        if (!empty($keys)) {
            $data = array_map(
                fn($item) => array_intersect_key($item, array_combine($keys, $keys)),
                $data
            );
        }

        $expectedHospitals = array_map(fn($datum) => array_map(fn($lang) => $lang ?: null, $datum), $data);
        $expectedHospitals = array_map(fn($datum) => implode('|', $datum), $expectedHospitals);
        $expectedHospitals = implode('|', $expectedHospitals);
        $expectedHospitals = explode('|', $expectedHospitals);
        $expectedHospitals = array_unique($expectedHospitals);
        $expectedHospitals = array_filter($expectedHospitals);

        return $expectedHospitals;
    }
}
