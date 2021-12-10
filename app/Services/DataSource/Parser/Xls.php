<?php

namespace App\Services\DataSource\Parser;

use App\Interfaces\DataSource\Parser;
use Illuminate\Support\Collection;

class Xls implements Parser
{
    const MIMES = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public static function factory(): self
    {
        return new static();
    }

    public function parse($resource): Collection
    {
        if (!$this->isValidMime($resource)) {
            throw new \InvalidArgumentException(sprintf(
                'invalid file type, expected `%s`, got %s',
                implode(', ', static::MIMES),
                $this->getMime($resource)
            ));
        }

        //  @todo (Pablo 2021-12-08) - parse into a collection
    }

    protected function isValidMime($resource): bool
    {
        return in_array($this->getMime($resource), static::MIMES);
    }

    protected function getMime($resource): ?string
    {
        return mime_content_type($resource) ?: null;
    }
}
