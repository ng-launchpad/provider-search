<?php

namespace App\Services\DataSource\Interfaces;

use Illuminate\Support\Collection;

interface Parser
{
    public static function factory(int $offset = 0): self;

    /**
     * Parse the given file into a collection
     *
     * @param $resource
     *
     * @return Collection
     */
    public function parse($resource): Collection;
}
