<?php

namespace App\Services\DataSource\Parser;

use App\Interfaces\DataSource\Parser;
use Illuminate\Support\Collection;

class Xls implements Parser
{
    const MIME = 'application/vnd.ms-excel';

    public function parse($resource): Collection
    {
        if (!$this->isValidMime($resource)) {
            throw new \InvalidArgumentException(sprintf(
                'invalid file type, expected `%s`, got %s',
                static::MIME,
                $this->getMime($resource)
            ));
        }

        //  @todo (Pablo 2021-12-08) - parse into a collection
    }

    protected function isValidMime($resource): bool
    {
        return $this->getMime($resource) === static::MIME;
    }

    protected function getMime($resource): ?string
    {
        return mime_content_type($resource) ?: null;
    }
}
