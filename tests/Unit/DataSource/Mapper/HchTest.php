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
use App\Services\DataSource\Mapper\Hch;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class HchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_extracts_the_languages()
    {
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data          = $this->getLanguageData();
        $expectedLangs = $this->getGeneratedLanguagesFromData($data);
        $collection    = new Collection($data);
        $mapper        = Hch::factory();

        $mapper->setVersion(Setting::nextVersion());

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
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getLocationData();
        $collection = new Collection($data);
        $mapper     = Hch::factory();

        $mapper->setVersion(Setting::nextVersion());

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum[Hch::COL_SERVICE_LOCATION_STATE];
            $state->code  = $datum[Hch::COL_SERVICE_LOCATION_STATE];
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
        $mapper     = Hch::factory();

        $mapper->setVersion(Setting::nextVersion());

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
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getHospitalData();
        $collection = new Collection($data);
        $mapper     = Hch::factory();

        $mapper->setVersion(Setting::nextVersion());

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
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getProviderData();
        $collection = new Collection($data);
        $mapper     = Hch::factory();
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
        $mapper     = Hch::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum[Hch::COL_SERVICE_LOCATION_STATE];
            $state->code  = $datum[Hch::COL_SERVICE_LOCATION_STATE];
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
                [$provider, $location, $is_primary, $phone] = $set;
                $provider
                    ->locations()
                    ->attach(
                        $location,
                        [
                            'is_primary' => $is_primary,
                            'phone'      => $phone,
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
        $mapper     = Hch::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        //  Calculate the expected languages
        $expectedLangs = array_map(function ($item) {
            return $this->getGeneratedLanguagesFromData([$item], [
                Hch::COL_FOREIGN_LANGUAGE1,
                Hch::COL_FOREIGN_LANGUAGE2,
                Hch::COL_FOREIGN_LANGUAGE3,
                Hch::COL_FOREIGN_LANGUAGE4,
                Hch::COL_FOREIGN_LANGUAGE5,
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
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getProviderSpecialityData();
        $collection = new Collection($data);
        $mapper     = Hch::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        //  Calculate the expected specialities
        $expectedSpecialities = array_map(function ($item) {
            return $this->getGeneratedSpecialitiesFromData([$item], [
                Hch::COL_PRIMARY_PROVIDER_SPECIALTY,
                Hch::COL_SPECIALTY_2,
                Hch::COL_SPECIALTY_3,
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
        self::markTestSkipped('Broken due to refactor, must be revisited');
        // arrange
        $data       = $this->getProviderHospitalData();
        $collection = new Collection($data);
        $mapper     = Hch::factory();
        $network    = Network::factory()->create();

        $mapper->setVersion(Setting::nextVersion());

        //  Calculate the expected hospitals
        $expectedHospitals = array_map(function ($item) {
            return $this->getGeneratedHospitalsFromData([$item], [
                Hch::COL_HOSPITAL_AFFILIATION_1,
                Hch::COL_HOSPITAL_AFFILIATION_2,
                Hch::COL_HOSPITAL_AFFILIATION_3,
                Hch::COL_HOSPITAL_AFFILIATION_4,
                Hch::COL_HOSPITAL_AFFILIATION_5,
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
            Hch::COL_FOREIGN_LANGUAGE1 => $faker->optional()->randomElement($languages),
            Hch::COL_FOREIGN_LANGUAGE2 => $faker->optional()->randomElement($languages),
            Hch::COL_FOREIGN_LANGUAGE3 => $faker->optional()->randomElement($languages),
            Hch::COL_FOREIGN_LANGUAGE4 => $faker->optional()->randomElement($languages),
            Hch::COL_FOREIGN_LANGUAGE5 => $faker->optional()->randomElement($languages),
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
            Hch::COL_MEDICAL_GROUP_NAME                    => $faker->company,
            Hch::COL_SERVICE_LOCATION_LINE_1               => $faker->streetAddress,
            Hch::COL_SERVICE_LOCATION_LINE_2               => $faker->streetAddress,
            Hch::COL_SERVICE_LOCATION_CITY                 => $faker->city,
            Hch::COL_SERVICE_LOCATION_STATE                => $faker->stateAbbr,
            Hch::COL_SERVICE_LOCATION_ZIP_CODE             => $faker->postcode,
            Hch::COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER => $faker->phoneNumber,
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
            Hch::COL_PRIMARY_PROVIDER_SPECIALTY => $faker->unique()->randomElement($specialities),
            Hch::COL_SPECIALTY_2                => $faker->unique()->randomElement($specialities),
            Hch::COL_SPECIALTY_3                => $faker->unique()->randomElement($specialities),
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
            Hch::COL_HOSPITAL_AFFILIATION_1 => $faker->unique()->company,
            Hch::COL_HOSPITAL_AFFILIATION_2 => $faker->unique()->company,
            Hch::COL_HOSPITAL_AFFILIATION_3 => $faker->unique()->company,
            Hch::COL_HOSPITAL_AFFILIATION_4 => $faker->unique()->company,
            Hch::COL_HOSPITAL_AFFILIATION_5 => $faker->unique()->company,
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
        $gender     = $faker->optional()->randomElement(['M', 'F']);
        $isFacility = $faker->boolean;

        return [
            Hch::COL_PROVIDER_FIRST_NAME        => $isFacility ? '' : $faker->name($gender),
            Hch::COL_PROVIDER_LAST_NAME         => $isFacility ? '' : $faker->lastName,
            Hch::COL_MEDICAL_GROUP_NAME         => $faker->company,
            Hch::COL_PROVIDER_TYPE              => $faker->word,
            Hch::COL_PROVIDER_GENDER            => $gender ? substr($gender, 0, 1) : '',
            Hch::COL_ACCEPTING_NEW_PATIENTS_IND => $faker->boolean,
            Hch::COL_NPI                        => $faker->unique()->numerify('##########'),
            Hch::COL_INFORMATION_TYPE_CODE      => $isFacility ? 'N' : 'I',
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
