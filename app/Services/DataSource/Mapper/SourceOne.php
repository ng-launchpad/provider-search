<?php

namespace App\Services\DataSource\Mapper;

use App\Interfaces\DataSource\Mapper;
use App\Models\Provider;

class SourceOne implements Mapper
{
    public function transform(array $item): Provider
    {
        //  @todo (Pablo 2021-12-08) - take the item and transform into a provider
    }
}
