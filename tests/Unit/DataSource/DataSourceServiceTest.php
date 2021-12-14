<?php

namespace Tests\Unit\DataSource;

use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Speciality;
use App\Models\State;
use App\Services\DataSource\Interfaces\Connection;
use App\Services\DataSource\Interfaces\Mapper;
use App\Services\DataSource\Interfaces\Parser;
use App\Models\Provider;
use App\Services\DataSourceService;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class DataSourceServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_truncates_the_data()
    {
        // arrange
        State::factory()->create();
        Provider::factory()->create();
        Network::factory()->create();
        Language::factory()->create();
        Location::factory()->create();
        Speciality::factory()->create();
        $service = DataSourceService::factory();

        // act
        $service->truncate();

        // assert
        $this->assertcount(1, State::all());
        $this->assertcount(0, Provider::all());
        $this->assertcount(0, Network::all());
        $this->assertcount(0, Language::all());
        $this->assertcount(0, Location::all());
        $this->assertcount(0, Speciality::all());
    }

    /** @test */
    public function it_syncs_data()
    {
        // arrange
        $connection = $this->createMock(Connection::class);
        $faker      = Factory::create();
        $network    = Network::factory()->create();

        /**
         * Create a new model, but not synced to the database;
         * include minimum required fields to avoid DB exceptions
         */
        $provider                            = new Provider();
        $provider->label                     = $faker->company;
        $provider->npi                       = $faker->numerify('##########');
        $provider->gender                    = $faker->randomElement([Provider::GENDER_MALE, Provider::GENDER_FEMALE]);
        $provider->network_id                = $network->id;
        $provider->is_facility               = $faker->boolean;
        $provider->is_accepting_new_patients = $faker->boolean;

        $mapper = $this->createMock(Mapper::class);
        $mapper
            ->method('transform')
            ->willReturn($provider);

        $collection = new Collection([
            ['actual content does not matter'],
        ]);

        $parser = $this->createMock(Parser::class);
        $parser
            ->method('parse')
            ->willReturn($collection);

        $service = DataSourceService::factory();

        // act
        $service->sync('test.file', $connection, $mapper, $parser);

        // assert
        $this->assertcount(1, Provider::all());
    }
}
