<?php

namespace Tests\Unit\DataSource\Mapper;

use App\Models\Language;
use App\Services\DataSource\Mapper\Aenta;
use Faker\Factory;
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
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }

    /** @test */
    public function it_extracts_the_specialities()
    {
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }

    /** @test */
    public function it_extracts_the_hospitals()
    {
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }

    /** @test */
    public function it_extracts_the_providers()
    {
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }

    /** @test */
    public function it_extracts_the_provider_locations()
    {
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }

    /** @test */
    public function it_extracts_the_provider_languages()
    {
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }

    /** @test */
    public function it_extracts_the_provider_specialities()
    {
        self::markTestIncomplete();
        // arrange

        // act

        // assert
    }

    /** @test */
    public function it_extracts_the_provider_hospitals()
    {
        self::markTestIncomplete();
        // arrange

        // act

        // assert
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
}
