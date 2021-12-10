<?php

namespace App\Services\DataSource\Connection;

use App\Interfaces\DataSource\Connection;
use League\Flysystem\FilesystemInterface;

class Sftp implements Connection
{
    private FilesystemInterface $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    public function download(string $file, $resource): Connection
    {
        //  @todo (Pablo 2021-12-08) - stream $file into $resource
        return $this;
    }
}
