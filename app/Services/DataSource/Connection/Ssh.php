<?php

namespace App\Services\DataSource\Connection;

use App\Services\DataSource\Interfaces\Connection;

final class Ssh implements Connection
{
    private \Spatie\Ssh\Ssh $ssh;

    public static function factory(
        string $host,
        string $username,
        int $port
    ) {
        return new \Spatie\Ssh\Ssh($username, $host, $port);
    }

    public function __construct(\Spatie\Ssh\Ssh $ssh)
    {
        $this->ssh = $ssh;
    }

    public function download(string $file, $resource): Connection
    {
        $this->ssh->download(
            $file,
            stream_get_meta_data($resource)['uri']
        );
        return $this;
    }
}
