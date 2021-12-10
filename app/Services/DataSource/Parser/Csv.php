<?php

namespace App\Services\DataSource\Parser;

use App\Interfaces\DataSource\Parser;
use Illuminate\Support\Collection;

class Csv implements Parser
{
    const MIMES = ['text/csv', 'text/plain'];

    public function parse($resource): Collection
    {
        if (!$this->isValidMime($resource)) {
            throw new \RuntimeException(sprintf(
                'Invalid file type, expected `%s`, got `%s`',
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
