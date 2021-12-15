<?php

namespace App\Services\DataSource\Mapper;

use App\Services\DataSource\Mapper;

final class Hch extends Mapper
{
    protected function getMap(): array
    {
        return [
            'COLUMN NAME' => 'label',
        ];
    }

    protected function getLanguageKeys(): array
    {
        return [];
    }

    protected function getLocationKeys(): array
    {
        return [];
    }

    protected function getSpecialityKeys(): array
    {
        return [];
    }

    protected function getProviderKeys(): array
    {
        return [];
    }
}
