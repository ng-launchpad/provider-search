<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Models\Provider;
use App\Models\Network;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderSingleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_provider()
    {
        // arrange
        $network  = Network::factory()->create();
        $provider = Provider::factory()->for($network)->create();

        // act
        $response = $this->withoutExceptionHandling()
            ->getJson(route('api.providers.single', $provider));

        // assert
        $response
            ->assertOk()
            ->assertJsonPath('data.label', $provider->label);
    }
}
