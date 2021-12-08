<?php

namespace Tests\Feature\Endpoints\Provider;

use App\Models\Provider;
use App\Models\State;
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
        $state = State::factory()
            ->create([
                'label' => 'Texas',
                'code'  => 'TX',
            ]);

        $provider = Provider::factory()
            ->for($state)
            ->create([
                'label' => 'FizzBuzz Inc',
            ]);

        // act
        $response = $this->withoutExceptionHandling()
            ->getJson(route('api.providers.single', $provider));

        // assert
        $response
            ->assertOk()
            ->assertJsonPath('data.label', $provider->label);
    }
}
