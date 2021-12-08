<?php

namespace Tests\Feature\Commands\DataSource;

use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_executes_successfully()
    {
        $this
            ->artisan('datasource:sync')
            ->assertExitCode(Command::SUCCESS);
    }
}
