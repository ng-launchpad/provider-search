<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_seeds_the_database()
    {
        // act
        $this->seed(DatabaseSeeder::class);

        // assert
        $this->assertTrue(User::count() > 0);
    }
}
