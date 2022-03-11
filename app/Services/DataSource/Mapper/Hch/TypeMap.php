<?php

namespace App\Services\DataSource\Mapper\Hch;

final class TypeMap
{
    const MAP = [
        'MD'  => 'Doctor Of Medicine',
        'DC'  => 'Doctor Of Chiropractic',
        'DO'  => 'Doctor Of Osteopathy',
        'DPM' => 'Doctor Of Podiatric Medicine',
        'PT'  => 'Physical Therapist/Physiotherapist',
        'PC'  => 'Professional Counselor',
        'CP'  => 'Clinical Psychologist',
    ];

    public static function lookup(string $key): ?string
    {
        return self::MAP[$key] ?? null;
    }
}
