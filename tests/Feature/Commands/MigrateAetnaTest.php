<?php

namespace Tests\Feature\Commands;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Speciality;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
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

    protected function createHospitals()
    {
        // create hospital
        $hospital = Hospital::factory()->create();

        // create aetna hospital
        $aetna_hospital = new Hospital();
        $aetna_hospital->setTable('aetna_hospitals');
        $aetna_hospital->create($hospital->toArray());

        // create unique aetna hospital
        $aetna_hospital = new Hospital();
        $aetna_hospital->setTable('aetna_hospitals');
        $aetna_hospital->create(Hospital::factory()->make()->toArray());
    }

    protected function createLanguages()
    {
        // create language
        $language = Language::factory()->create();

        // create aetna language
        $aetna_language = new Language();
        $aetna_language->setTable('aetna_languages');
        $aetna_language->create($language->toArray());

        // create unique aetna language
        $aetna_language = new Language();
        $aetna_language->setTable('aetna_languages');
        $aetna_language->create(Language::factory([
            'label' => $language->label.' modified'
        ])->make()->toArray());
    }

    protected function createSpecialities()
    {
        // create speciality
        $speciality = Speciality::factory()->create();

        // create aetna speciality
        $aetna_speciality = new Speciality();
        $aetna_speciality->setTable('aetna_specialities');
        $aetna_speciality->create($speciality->toArray());

        // create unique aetna speciality
        $aetna_speciality = new Speciality();
        $aetna_speciality->setTable('aetna_specialities');
        $aetna_speciality->create(Speciality::factory([
            'label' => $speciality->label.' modified'
        ])->make()->toArray());
    }
}
