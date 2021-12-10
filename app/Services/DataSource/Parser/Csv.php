<?php

namespace App\Services\DataSource\Parser;

use App\Interfaces\DataSource\Parser;
use Illuminate\Support\Collection;

class Csv implements Parser
{
    const MIMES = ['text/csv', 'text/plain'];

    public static function factory(): self
    {
        return new static();
    }

    public function parse($resource): Collection
    {
        if (!$this->isValidMime($resource)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid file type, expected `%s`, got `%s`',
                implode(', ', static::MIMES),
                $this->getMime($resource)
            ));
        }

        $collection = new Collection();

        rewind($resource);

        while (($data = fgetcsv($resource)) !== false) {
            $collection->add($data);
        }

        return $collection;
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
