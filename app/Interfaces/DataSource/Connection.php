<?php

namespace App\Interfaces\DataSource;

interface Connection
{
    /**
     * Downloads the file to the given resource
     *
     * @param string   $file
     * @param resource $resource
     *
     * @return $this
     */
    public function download(string $file, $resource): self;
}
