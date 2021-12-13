<?php

namespace Tests\Feature\Endpoints\State;

use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StateIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_list_of_states()
    {
        // arrange
        $state = State::factory()->create();

        // act
        $response = $this
            ->withoutExceptionHandling()
            ->getJson(route('api.states.index'));

        // assert
        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.label', $state->label);
    }
}
