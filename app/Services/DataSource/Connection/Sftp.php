<?php

namespace App\Services\DataSource\Connection;

use App\Interfaces\DataSource\Connection;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Sftp\SftpAdapter;

class Sftp implements Connection
{
    private FilesystemInterface $filesystem;

    public static function factory(
        string $host,
        string $username,
        string $password,
        int $port = 22,
        string $privateKey = null,
        string $root = '~/',
        int $timeout = 10
    ) {
        return new static(
            new Filesystem(
                new SftpAdapter([
                    'host'       => $host,
                    'username'   => $username,
                    'password'   => $password,
                    'port'       => $port,
                    'privateKey' => $privateKey,
                    'root'       => $root,
                    'timeout'    => $timeout,
                ])
            )
        );
    }

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function download(string $file, $resource): Connection
    {
        $response = $this->filesystem->read($file);
        fwrite($resource, $response);
        return $this;
    }
}
