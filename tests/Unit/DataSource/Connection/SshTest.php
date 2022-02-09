<?php

namespace Tests\Unit\DataSource\Connection;

use App\Services\DataSource\Connection\Ssh;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class SshTest extends TestCase
{
    /** @test */
    public function it_returns_the_most_recently_modified_file()
    {
        self::markTestIncomplete();
    }

    /** @test */
    public function it_downloads_from_the_remote_server()
    {
        // arrange
        $content  = 'Inceptos Vehicula Porta Adipiscing Egestas';
        $resource = tmpfile();
        $ssh      = $this->createMock(\Spatie\Ssh\Ssh::class);
        $ssh
            ->method('download')
            ->willReturnCallback(function () use ($resource, $content) {
                fwrite($resource, $content);
                return new Process(['whoami']);
            });

        $connection = new Ssh($ssh);

        // act
        $connection->download('test.txt', $resource);
        rewind($resource);

        // assert
        $this->assertEquals(
            $content,
            fread(
                $resource,
                strlen($content)
            )
        );
    }
}
