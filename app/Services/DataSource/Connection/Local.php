<?php

namespace App\Services\DataSource\Connection;

use App\Services\DataSource\Interfaces\Connection;

final class Local implements Connection
{
    private ?string $path;

    public static function factory(string $path = null): self
    {
        return new self($path);
    }

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function download(string $path, $resource): Connection
    {
        fwrite($resource, file_get_contents($this->path));
        return $this;
    }
}
