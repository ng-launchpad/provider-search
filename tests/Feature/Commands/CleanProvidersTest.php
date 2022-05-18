<?php

namespace Tests\Feature\Commands;

use App\Models\Network;
use App\Models\Provider;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CleanProvidersTest extends TestCase
{
    use RefreshDatabase;

    /** @var Provider */
    private $provider;

    public function setUp(): void
    {
        parent::setUp();

        Network::factory()->create();
        $this->provider = Provider::factory()->create();

        // create strict matching provider
        Provider::factory()->create([
            'label' => $this->provider->label,
            'type' => $this->provider->type,
            'npi' => $this->provider->npi,
            'degree' => $this->provider->degree,
            'website' => $this->provider->website,
            'gender' => $this->provider->gender,
            'is_facility' => $this->provider->is_facility,
            'is_accepting_new_patients' => $this->provider->is_accepting_new_patients,
            'network_id' => Network::factory(),
        ]);

        // create loose matching provider
        Provider::factory()->create([
            'label' => $this->provider->label,
            'npi' => $this->provider->npi,
            'is_facility' => $this->provider->is_facility,
            'network_id' => Network::factory(),
        ]);

        // create npi matching provider
        Provider::factory()->create([
            'npi' => $this->provider->npi,
            'network_id' => Network::factory(),
        ]);
    }

    /** @test */
    public function it_cleanups_providers_strict()
    {
        // arrange
        $provider = $this->provider;

        // act
        $exitcode = Artisan::call('app:clean-providers --mode=strict');

        // dd(Provider::count());

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue(Provider::count() == 3);
    }

    /** @test */
    public function it_cleanups_providers_loose()
    {
        // arrange
        $provider = $this->provider;

        // act
        $exitcode = Artisan::call('app:clean-providers --mode=loose');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue(Provider::count() == 2);
    }

    /** @test */
    public function it_cleanups_providers_npi()
    {
        // arrange
        $provider = $this->provider;

        // act
        $exitcode = Artisan::call('app:clean-providers --mode=npi');

        // assert
        $this->assertTrue($exitcode == 0);
        $this->assertTrue(Provider::count() == 1);
    }
}
