<?php

namespace App\Services\DataSource\Connection;

use App\Services\DataSource\Interfaces\Connection;

final class Ssh implements Connection
{
    private \Spatie\Ssh\Ssh $ssh;

    public static function factory(
        string $username = '',
        string $host = '',
        int $port = null,
        string $private_key = ''
    ): self {

        $ssh = new \Spatie\Ssh\Ssh($username, $host, $port);
        $ssh->usePrivateKey($private_key);

        return new self($ssh);
    }

    public function __construct(\Spatie\Ssh\Ssh $ssh)
    {
        $this->ssh = $ssh;
    }

    public function getMostRecentlyModified(string $path): ?array
    {
        $output = $this->ssh->execute('ls -al ' . $path)->getOutput();

        //  @todo (Pablo 2022-02-09) - parse output and return most recently modified file

        return [];
    }

    public function download(string $path, $resource): Connection
    {
        $this->ssh->download(
            $this->getMostRecentlyModified($path)['path'] ?? null,
            stream_get_meta_data($resource)['uri']
        );
        return $this;
    }
}
