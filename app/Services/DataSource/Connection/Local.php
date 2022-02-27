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

    public function download(string $path, &$resource): Connection
    {
        $resource = fopen($this->path, 'r');
        return $this;
    }

    public function getMostRecentlyModified(string $path): ?array
    {
        $files = glob($path . '/*');
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        return array_shift($files);
    }
}
