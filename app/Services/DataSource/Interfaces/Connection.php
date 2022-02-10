<?php

namespace App\Services\DataSource\Interfaces;

interface Connection
{
    public static function factory(): self;

    /**
     * Returns the most recently modified file from the given path
     *
     * @param string $path
     *
     * @return array|null
     */
    public function getMostRecentlyModified(string $path): ?array;

    /**
     * Downloads the file to the given resource
     *
     * @param string   $path
     * @param resource $resource
     *
     * @return $this
     */
    public function download(string $path, $resource): self;
}
