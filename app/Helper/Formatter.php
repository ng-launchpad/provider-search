<?php

namespace App\Helper;

final class Formatter
{
    public static function phone(string $number): string
    {
        if (preg_match('/\d{10,}/', $number)) {
            return preg_replace('/(\d{3,})(\d{3,})(\d{4,})/', '($1) $2-$3', $number);
        }
        return $number;
    }
}
