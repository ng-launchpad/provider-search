<?php

namespace App\Traits;

trait HasGetTableName
{
    public static function getTableName(): string
    {
        return (new static())->getTable();
    }
}
