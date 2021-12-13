<?php

namespace App\Services\DataSource\Interfaces;

use App\Models\Provider;

interface Mapper
{
    /**
     * Takes a given item in an expected format and transforms it into a Provider
     *
     * @param array $item
     *
     * @return Provider
     */
    public function transform(array $item): Provider;
}
