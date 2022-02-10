<?php

namespace App\Models\Concerns;

trait HasGetTableName
{
    public static function getTableName(): string
    {
        /** @phpstan-ignore-next-line */
        return (new static())->getTable();
    }
}
