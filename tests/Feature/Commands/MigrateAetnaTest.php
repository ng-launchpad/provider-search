<?php

namespace Tests\Feature\Commands;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Provider;
use App\Models\Speciality;
use App\Models\State;
use Database\Seeders\NetworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MigrateAetnaTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_migrates_aetna_hospitals()
    {
        // arrange
        $this->createHospitals();

        // act
        $exitcode = Artisan::call('app:migrate-aetna');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue(Hospital::count() == 2);
    }

    /** @test */
    public function it_migrates_aetna_languages()
    {
        // arrange
        $this->createLanguages();

        // act
        $exitcode = Artisan::call('app:migrate-aetna');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue(Language::count() == 2);
    }

    /** @test */
    public function it_migrates_aetna_specialities()
    {
        // arrange
        $this->createSpecialities();

        // act
        $exitcode = Artisan::call('app:migrate-aetna');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue(Speciality::count() == 2);
    }

    /** @test */
    public function it_migrates_aetna_locations()
    {
        // arrange
        $this->createLocations();

        // act
        $exitcode = Artisan::call('app:migrate-aetna');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue(Location::count() == 2);
    }

    /** @test */
    public function it_migrates_aetna_providers()
    {
        // arrange
        $this->createProviders();

        // act
        $exitcode = Artisan::call('app:migrate-aetna');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue(Provider::count() == 2);

        $provider = Provider::first();
        $this->assertTrue($provider->locations()->count() == 1);
        $this->assertTrue($provider->hospitals()->count() == 1);
        $this->assertTrue($provider->languages()->count() == 1);
        $this->assertTrue($provider->specialities()->count() == 1);
    }

    protected function createHospitals()
    {
        // create hospital
        $hospital = Hospital::factory()->create();

        // create aetna hospital
        $aetna_hospital = Hospital::make()
            ->setTable('aetna_hospitals')
            ->create($hospital->toArray());

        // create unique aetna hospital
        $aetna_hospital = Hospital::make()
            ->setTable('aetna_hospitals')
            ->create(Hospital::factory()->make()->toArray());
    }

    protected function createLanguages()
    {
        // create language
        $language = Language::factory()->create();

        // create aetna language
        $aetna_language = Language::make()
            ->setTable('aetna_languages')
            ->create($language->toArray());

        // create unique aetna language
        $aetna_language = Language::make()
            ->setTable('aetna_languages')
            ->create(Language::factory([
            'label' => $language->label.' modified'
        ])->make()->toArray());
    }

    protected function createSpecialities()
    {
        // create speciality
        $speciality = Speciality::factory()->create();

        // create aetna speciality
        $aetna_speciality = Speciality::make()
            ->setTable('aetna_specialities')
            ->create($speciality->toArray());

        // create unique aetna speciality
        $aetna_speciality = Speciality::make()
            ->setTable('aetna_specialities')
            ->create(Speciality::factory()->make()->toArray());
    }

    protected function createLocations()
    {
        // create location
        $state = State::factory()->create();
        $location = Location::factory()->create();

        // create aetna location
        $aetna_location = Location::make()
            ->setTable('aetna_locations')
            ->create($location->toArray());

        // create unique aetna location
        $aetna_location = Location::make()
            ->setTable('aetna_locations')
            ->create(Location::factory()->make()->toArray());
    }

    protected function createProviders()
    {
        $this->seed(NetworkSeeder::class);

        // create provider
        $provider = Provider::factory()->create();

        // create aetna provider
        DB::table('aetna_providers')
            ->insert(array_merge($provider->toArray(), [
                'id' => 'DF-1'
            ]));
        $aetna_provider = DB::table('aetna_providers')->first();

        // attach location
        $state = State::factory()->create();
        $aetna_location = Location::make()
            ->setTable('aetna_locations')
            ->create(Location::factory()->make()->toArray());

        DB::table('aetna_location_provider')->insert([
            'location_id' => $aetna_location->id,
            'provider_id' => $aetna_provider->id,
        ]);

        // attach hospital
        $aetna_hospital = Hospital::make()
            ->setTable('aetna_hospitals')
            ->create(Hospital::factory()->make()->toArray());

        DB::table('aetna_hospital_provider')->insert([
            'hospital_id' => $aetna_hospital->id,
            'provider_id' => $aetna_provider->id,
        ]);

        // attach language
        $aetna_language = Language::make()
            ->setTable('aetna_languages')
            ->create(Language::factory()->make()->toArray());

        DB::table('aetna_language_provider')->insert([
            'language_id' => $aetna_language->id,
            'provider_id' => $aetna_provider->id,
        ]);

        // attach speciality
        $aetna_speciality = Speciality::make()
            ->setTable('aetna_specialities')
            ->create(Speciality::factory()->make()->toArray());

        DB::table('aetna_provider_speciality')->insert([
            'speciality_id' => $aetna_language->id,
            'provider_id' => $aetna_provider->id,
        ]);

        // create unique aetna provider
        DB::table('aetna_providers')
            ->insert(array_merge(Provider::factory()->make()->toArray(), [
                'id' => 'DF-2'
            ]));
    }
}
