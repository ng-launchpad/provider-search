<?php

namespace App\Services\DataSource\Mapper;

use App\Services\DataSource\Interfaces\Mapper;
use App\Models\Provider;

final class Vsp implements Mapper
{
    public static function factory(): self
    {
        return new self();
    }

    public function transform(array $item): Provider
    {
        $provider = new Provider();

        $provider->label = $item['PRACTICE NAME'];

        return $provider;
    }
}
