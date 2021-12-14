<?php

namespace Tests\Unit\DataSource\Mapper;

use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Models\State;
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
        self::markTestIncomplete();
        // arrange
        $data       = $this->getLanguageData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();

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
        self::markTestIncomplete();
        // arrange
        $data       = $this->getLocationData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();

        //  Ensure generated states exist
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
        self::markTestIncomplete();
        // arrange
        $data       = $this->getNetworkData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();

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
        self::markTestIncomplete();
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
        $this->assertCount(0, Speciality::all());
    }

    /** @test */
    public function it_extracts_the_providers()
    {
        self::markTestIncomplete();
        // arrange
        $data       = $this->getProviderData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();

        //  Ensure generated Networks exist
        foreach ($data as $datum) {
            $state        = new Network();
            $state->label = $datum['PROVIDER NETWORK'];
            $state->save();
        }

        // act
        $mapper
            ->extractProviders($collection)
            ->unique()
            ->each(fn(Provider $model) => $model->save());

        // assert
        $this->assertCount(4, Speciality::all());
    }

    private function getLocationData(): array
    {
        return [];
    }

    private function getLanguageData(): array
    {
        return [];
    }

    private function getNetworkData(): array
    {
        return [];
    }

    private function getSpecialityData(): array
    {
        return [];
    }

    private function getProviderData(): array
    {
        return [];
    }
}
