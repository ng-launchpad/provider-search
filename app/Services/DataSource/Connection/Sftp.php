<?php

namespace App\Services\DataSource\Connection;

use App\Interfaces\DataSource\Connection;

class Sftp implements Connection
{
    public function download(string $file, $resource): Connection
    {
        //  @todo (Pablo 2021-12-08) - stream $file into $resource
        return $this;
    }
}
