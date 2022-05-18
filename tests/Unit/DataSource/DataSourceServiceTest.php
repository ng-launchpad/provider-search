<?php

namespace Tests\Unit\DataSource;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Setting;
use App\Models\Speciality;
use App\Models\State;
use App\Notifications\SyncFailureNotification;
use App\Services\DataSource\Interfaces\Connection;
use App\Services\DataSource\Interfaces\Mapper;
use App\Services\DataSource\Interfaces\Parser;
use App\Models\Provider;
use App\Services\DataSourceService;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Console\Output\ConsoleOutput;
use Tests\TestCase;

class DataSourceServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_truncates_the_data()
    {
        // arrange
        //  non-versioned items
        $state   = State::factory()->create();
        $network = Network::factory()->create();

        // versioned items
        Provider::factory()->for($network)->create();
        Hospital::factory()->create();
        Language::factory()->create();
        Location::factory()->for($state)->create();
        Speciality::factory()->create();

        // versioned items but in a different version
        Provider::factory()->for($network)->create(['version' => Setting::nextVersion()]);
        Hospital::factory()->create(['version' => Setting::nextVersion()]);
        Language::factory()->create(['version' => Setting::nextVersion()]);
        Location::factory()->for($state)->create(['version' => Setting::nextVersion()]);
        Speciality::factory()->create(['version' => Setting::nextVersion()]);

        $service = DataSourceService::factory();

        // act
        $service->truncate(Setting::version());

        // assert
        $this->assertcount(1, State::all());
        $this->assertcount(1, Network::all());
        $this->assertcount(1, Provider::all());
        $this->assertcount(1, Hospital::all());
        $this->assertcount(1, Language::all());
        $this->assertcount(1, Location::all());
        $this->assertcount(1, Speciality::all());
    }

    /** @test */
    public function it_detects_zip_file()
    {
        // arrange
        $service  = DataSourceService::factory();
        $file     = tempnam(sys_get_temp_dir(), 'FOO');
        $contents = 'Curabitur blandit tempus porttitor.';
        $zip      = new \ZipArchive();

        $zip->open($file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('data.txt', $contents);
        $zip->close();

        $resource = fopen($file, 'r');

        // act
        $isZip = $service->isZip($resource);

        // assert
        self::assertTrue($isZip);
    }

    /** @test */
    public function it_detects_non_zip_file()
    {
        // arrange
        $service  = DataSourceService::factory();
        $file     = tempnam(sys_get_temp_dir(), 'FOO');
        $contents = 'Curabitur blandit tempus porttitor.';
        file_put_contents($file, $contents);

        $resource = fopen($file, 'r');

        // act
        $isZip = $service->isZip($resource);

        // assert
        self::assertFalse($isZip);
    }

    /** @test */
    public function it_unzips_the_file()
    {
        // arrange
        $service  = DataSourceService::factory();
        $file     = tempnam(sys_get_temp_dir(), 'FOO');
        $contents = 'Curabitur blandit tempus porttitor.';
        $zip      = new \ZipArchive();

        $zip->open($file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('data.txt', $contents);
        $zip->close();

        $resource = fopen($file, 'r');

        // act
        $newResource = $service->unzip($resource);

        // assert
        self::assertEquals($contents, file_get_contents(stream_get_meta_data($newResource)['uri']));
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
        $hospital1   = Hospital::factory()->make();
        $hospital2   = Hospital::factory()->make();

        //  Configure mocks: Connection
        $connection
            ->expects($this->once())
            ->method('download');

        //  Configure mocks: Parser
        $parser
            ->expects($this->once())
            ->method('parse')
            ->willReturn($this->arrayAsGenerator([
                [],
                [],
            ]));

        //  Configure mocks: Mapper
        $mapper
            ->method('skipRow')
            ->willReturn(false);

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

            'extractHospitals' => [
                $hospital1,
                $hospital2,
            ],

            'extractProviders' => array_map(
                function (Provider $provider) use ($network) {
                    $provider->network_id = $network->id;
                    return $provider;
                },
                [
                    $provider1,
                    $provider2,
                ]
            ),

            'extractProviderLocations' => [
                [$provider1, $location1, true],
                [$provider1, $location2, false],
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

            'extractProviderHospitals' => [
                [$provider1, $hospital1],
                [$provider1, $hospital2],
                [$provider2, $hospital1],
            ],
        ];

        foreach ($config as $method => $items) {
            $mapper
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
        $this->assertCount(2, Hospital::all());
        $this->assertCount(2, Provider::all());

        $this->assertCount(2, $provider1->locations()->get());
        $this->assertCount(2, $provider1->languages()->get());
        $this->assertCount(3, $provider1->specialities()->get());
        $this->assertCount(2, $provider1->hospitals()->get());

        $this->assertCount(1, $provider2->locations()->get());
        $this->assertCount(1, $provider2->languages()->get());
        $this->assertCount(1, $provider2->specialities()->get());
        $this->assertCount(1, $provider2->hospitals()->get());
    }

    /** @test */
    public function it_sends_error_notification()
    {
        // arrange
        Notification::fake();
        $service   = DataSourceService::factory();
        $message   = 'Something went wrong.';
        $code      = 123;
        $exception = new \Exception($message, $code);

        // act
        $service->notifyError($exception, []);

        // assert
        Notification::assertSentTo(
            new AnonymousNotifiable, SyncFailureNotification::class
        );
    }

    private function arrayAsGenerator(array $array): \Generator
    {
        foreach ($array as $item) {
            yield $item;
        }
    }
}
