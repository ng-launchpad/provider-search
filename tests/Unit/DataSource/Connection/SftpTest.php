<?php

namespace Tests\Unit\DataSource\Connection;

use App\Services\DataSource\Connection\Sftp;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Sftp\SftpAdapter;
use PHPUnit\Framework\TestCase;

class SftpTest extends TestCase
{
    /** @test */
    public function it_returns_the_most_recently_modified_file()
    {
        // arrange
        $path  = '/path/to/files';
        $files = [
            [
                'path'      => $path . '/file1.txt',
                'timestamp' => time() - 1,
            ],
            [
                'path'      => $path . '/file2.txt',
                'timestamp' => time(),
            ],
            [
                'path'      => $path . '/file3.txt',
                'timestamp' => time() + 1,
            ],
        ];

        $filesystem = $this->createMock(Filesystem::class);
        $adapter    = $this->createMock(SftpAdapter::class);

        $filesystem
            ->method('listContents')
            ->willReturn($files);

        $filesystem
            ->method('getAdapter')
            ->willReturn($adapter);

        $connection = new Sftp($filesystem);

        // act
        $file = $connection->getMostRecentlyModified($path);

        // assert
        $this->assertEquals($files[2], $file);
    }

    /** @test */
    public function it_downloads_from_the_remote_server()
    {
        // arrange
        $path    = '/path/to/files';
        $content = 'Etiam porta sem malesuada magna mollis euismod.';
        $files   = [
            [
                'path'      => $path . '/file1.txt',
                'timestamp' => time(),
            ],
        ];

        $filesystem = $this->createMock(Filesystem::class);
        $adapter    = $this->createMock(SftpAdapter::class);

        $filesystem
            ->method('listContents')
            ->willReturn($files);

        $filesystem
            ->method('getAdapter')
            ->willReturn($adapter);

        $filesystem
            ->method('read')
            ->willReturn($content);

        $connection = new Sftp($filesystem);
        $resource   = tmpfile();

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
