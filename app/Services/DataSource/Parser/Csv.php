<?php

namespace App\Services\DataSource\Parser;

use App\Services\DataSource\Interfaces\Parser;
use Illuminate\Support\Collection;

final class Csv implements Parser
{
    const MIMES = ['text/csv', 'application/csv', 'text/plain'];

    private int $offset;

    public static function factory(int $offset = 0): self
    {
        return new self($offset);
    }

    public function __construct(int $offset = 0)
    {
        $this->offset = $offset;
    }

    public function parse($resource): Collection
    {
        if (!$this->isValidMime($resource)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid file type, expected `%s`, got `%s`',
                implode(', ', self::MIMES),
                $this->getMime($resource)
            ));
        }

        $collection = new Collection();
        $i          = 0;

        rewind($resource);

        while (($data = fgetcsv($resource)) !== false) {
            if ($i >= $this->offset) {
                $collection->add($data);
            }
            $i++;
        }

        return $collection;
    }

    protected function isValidMime($resource): bool
    {
        return in_array($this->getMime($resource), self::MIMES);
    }

    protected function getMime($resource): ?string
    {
        return mime_content_type($resource) ?: null;
    }
}
