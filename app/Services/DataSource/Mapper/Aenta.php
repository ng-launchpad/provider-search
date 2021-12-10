<?php

namespace App\Services\DataSource\Mapper;

use App\Services\DataSource\Mapper;

final class Aenta extends Mapper
{
    protected function getMap(): array
    {
        return [
            'COLUMN NAME' => 'label',
        ];
    }
}
