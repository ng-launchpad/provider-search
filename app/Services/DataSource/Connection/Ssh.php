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

    public function download(string $path, $resource): Connection
    {
        $this->ssh->download(
            $path,
            stream_get_meta_data($resource)['uri']
        );
        return $this;
    }
}
