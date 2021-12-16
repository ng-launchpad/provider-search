<?php

namespace App\Traits;

trait HasGetTableName
{
    public static function getTableName(): string
    {
        /** @phpstan-ignore-next-line */
        return (new static())->getTable();
    }
}
