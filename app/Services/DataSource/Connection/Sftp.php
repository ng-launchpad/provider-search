<?php

namespace App\Services\DataSource\Connection;

use App\Services\DataSource\Interfaces\Connection;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Sftp\SftpAdapter;

final class Sftp implements Connection
{
    private FilesystemInterface $filesystem;

    public static function factory(
        string $host = null,
        string $username = null,
        string $password = null,
        int $port = 22,
        string $privateKey = null,
        string $root = '/',
        int $timeout = 10
    ): self {
        return new self(
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

    public function getMostRecentlyModified(string $path): ?array
    {
        $list = $this->filesystem->listContents($path);

        array_multisort(array_column($list, 'timestamp'), SORT_DESC, $list);

        return $list[0] ?? null;
    }

    public function download(string $path, $resource): Connection
    {
        $response = $this->filesystem->read(
            $this->getMostRecentlyModified($path)['path'] ?? null
        );

        fwrite($resource, $response);

        return $this;
    }
}
