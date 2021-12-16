<?php

namespace Tests\Unit\DataSource;

use App\Models\Hospital;
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
        $state      = State::factory()->create();
        $network    = Network::factory()->create();
        $provider   = Provider::factory()->for($network)->create();
        $hospital   = Hospital::factory()->create();
        $language   = Language::factory()->create();
        $location   = Location::factory()->for($state)->create();
        $speciality = Speciality::factory()->create();

        $service = DataSourceService::factory();

        // act
        $service->truncate();

        // assert
        $this->assertcount(1, State::all());
        $this->assertcount(1, Network::all());
        $this->assertcount(0, Provider::all());
        $this->assertcount(0, Hospital::all());
        $this->assertcount(0, Language::all());
        $this->assertcount(0, Location::all());
        $this->assertcount(0, Speciality::all());
    }

    /** @test */
    public function it_syncs_data()
    {
        // arrange
        $service    = DataSourceService::factory();
        $faker      = Factory::create();
        $connection = $this->createMock(Connection::class);
        $parser     = $this->createMock(Parser::class);
        $mapper     = $this->createMock(Mapper::class);

        // Create/make assets which will be referenced later
        $network     = Network::factory()->create();
        $state       = State::factory()->create();
        $provider1   = Provider::factory()->make();
        $provider2   = Provider::factory()->make();
        $location1   = Location::factory()->for($state)->make();
        $location2   = Location::factory()->for($state)->make();
        $language1   = Language::factory()->make(['label' => $faker->unique->languageCode]);
        $language2   = Language::factory()->make(['label' => $faker->unique->languageCode]);
        $speciality1 = Speciality::factory()->make(['label' => $faker->unique->word]);
        $speciality2 = Speciality::factory()->make(['label' => $faker->unique->word]);
        $speciality3 = Speciality::factory()->make(['label' => $faker->unique->word]);

        //  Configure mocks: Connection
        $connection
            ->expects($this->once())
            ->method('download');

        //  Configure mocks: Parser
        $parser
            ->expects($this->once())
            ->method('parse');

        //  Configure mocks: Mapper
        $config = [
            'extractLanguages' => [
                $language1,
                $language2,
            ],

            'extractLocations' => [
                $location1,
                $location2,
            ],

            'extractSpecialities' => [
                $speciality1,
                $speciality2,
                $speciality3,
            ],

            'extractProviders' => [
                $provider1,
                $provider2,
            ],

            'extractProviderLocations' => [
                [$provider1, $location1, true],
                [$provider1, $location2, true],
                [$provider2, $location2, true],
            ],

            'extractProviderLanguages' => [
                [$provider1, $language1],
                [$provider1, $language2],
                [$provider2, $language2],
            ],

            'extractProviderSpecialities' => [
                [$provider1, $speciality1],
                [$provider1, $speciality2],
                [$provider1, $speciality3],
                [$provider2, $speciality3],
            ],
        ];

        foreach ($config as $method => $items) {
            $mapper
                ->expects($this->once())
                ->method($method)
                ->willReturn(Collection::make($items));
        }

        // act
        $service->sync(
            $network,
            '/path/to/test.file',
            $connection,
            $parser,
            $mapper
        );

        // assert
        $this->assertCount(2, Language::all());
        $this->assertCount(2, Location::all());
        $this->assertCount(3, Speciality::all());
        $this->assertCount(2, Provider::all());
        $this->assertCount(2, $provider1->locations()->get());
        $this->assertCount(2, $provider1->languages()->get());
        $this->assertCount(3, $provider1->specialities()->get());
        $this->assertCount(1, $provider2->locations()->get());
        $this->assertCount(1, $provider2->languages()->get());
        $this->assertCount(1, $provider2->specialities()->get());
    }
}
