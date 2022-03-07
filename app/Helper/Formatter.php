<?php

namespace App\Helper;

final class Formatter
{
    public static function phone(string $number): string
    {
        if (preg_match('/\d{10,}/', $number)) {
            return preg_replace('/(\d{3,})(\d{3,})(\d{4,})/', '($1) $2-$3', $number);

        } elseif (preg_match('/\(\d{3,}\)\d{3,}-\d{4,}/', $number)) {
            return preg_replace('/(\d)\)(\d)/', '$1) $2', $number);
        }

        return $number;
    }
}
