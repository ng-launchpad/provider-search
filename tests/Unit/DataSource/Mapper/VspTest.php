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
        $data          = $this->getLanguageData();
        $expectedLangs = $this->getGeneratedLanguagesFromData($data);
        $collection    = new Collection($data);
        $mapper        = Vsp::factory();

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
        $mapper     = Vsp::factory();
        $network    = Network::factory()->create();

        //  Ensure generated States exist
        foreach ($data as $datum) {
            $state        = new State();
            $state->label = $datum['ST'];
            $state->code  = $datum['ST'];
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
        $mapper     = Vsp::factory();
        $network    = Network::factory()->create();

        //  Calculate the expected languages
        $expectedLangs = array_map(function ($item) {
            return $this->getGeneratedLanguagesFromData([$item], [
                'LANGUAGE SPOKEN 1',
                'LANGUAGE SPOKEN 2',
                'LANGUAGE SPOKEN 3',
                'LANGUAGE SPOKEN 4',
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
                Language::create(['label' => $item]);
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
        self::markTestIncomplete();
    }

    /** @test */
    public function it_extracts_the_provider_hospitals()
    {
        self::markTestIncomplete();
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
            $this->getLanguageDatum(),
            $this->getLanguageDatum(),
        ];
    }

    private function getLanguageDatum(): array
    {
        $faker     = Factory::create();
        $languages = ['English', 'Spanish', 'French', 'German', 'Vietnamese', 'Chinese'];
        return [
            'LANGUAGE SPOKEN 1' => $faker->optional()->randomElement($languages),
            'LANGUAGE SPOKEN 2' => $faker->optional()->randomElement($languages),
            'LANGUAGE SPOKEN 3' => $faker->optional()->randomElement($languages),
            'LANGUAGE SPOKEN 4' => $faker->optional()->randomElement($languages),
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
        ];
    }

    private function getProviderLocationData(): array
    {
        $provider = $this->getProviderDatum();

        return [
            array_merge($provider, $this->getLocationDatum()),
            array_merge($provider, $this->getLocationDatum()),

            //  Third option which should be attached to a different Provider
            array_merge($this->getProviderDatum(), $this->getLocationDatum()),
        ];
    }

    private function getProviderLanguageData(): array
    {
        $provider = $this->getProviderDatum();

        return [
            array_merge($provider, $this->getLanguageDatum()),
            array_merge($this->getProviderDatum(), $this->getLanguageDatum()),
        ];
    }
}
