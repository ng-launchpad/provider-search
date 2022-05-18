<?php

namespace App\Services\DataSource\Mapper\Vsp;

final class TypeMap
{
    const MAP = [
        'MD' => 'Ophthalmologist',
        'DO' => 'Optometrist',
        'OD' => 'Optometrist',
    ];

    public static function lookup(string $key): ?string
    {
        return self::MAP[$key] ?? null;
    }
}
