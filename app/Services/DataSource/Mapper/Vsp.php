<?php

namespace App\Services\DataSource\Mapper;

use App\Services\DataSource\Mapper;

final class Vsp extends Mapper
{
    protected function getMap(): array
    {
        return [
            'PRACTICE NAME' => 'label',
        ];
    }
}
