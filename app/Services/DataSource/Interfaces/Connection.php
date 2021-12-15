<?php

namespace App\Services\DataSource\Interfaces;

interface Connection
{
    public static function factory(): self;

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
