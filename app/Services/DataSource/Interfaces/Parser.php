<?php

namespace App\Services\DataSource\Interfaces;

interface Parser
{
    public static function factory(int $offset = 0): self;

    /**
     * Parse the given file into a collection
     *
     * @param $resource
     *
     * @return \Generator
     */
    public function parse($resource): \Generator;
}
