<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProviderSingleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_list_of_providers()
    {
        // arrange
        $provider = Provider::factory()->create();

        // act
        $response = $this->withoutExceptionHandling()
            ->getJson(route('providers.single', $provider));

        // assert
        $response->assertOk();
    }
}
