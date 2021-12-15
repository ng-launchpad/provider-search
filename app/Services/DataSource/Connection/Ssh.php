<?php

namespace App\Services\DataSource\Connection;

use App\Services\DataSource\Interfaces\Connection;

final class Ssh implements Connection
{
    public function download(string $file, $resource): Connection
    {
        //  @todo (Pablo 2021-12-15) - implement this
        return $this;
    }
}
