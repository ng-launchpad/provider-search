<?php

namespace App\Services\DataSource\Parser;

use App\Services\DataSource\Interfaces\Parser;
use Illuminate\Support\Collection;

final class TextColumns implements Parser
{
    const MIMES = ['text/plain'];

    private array $columnMap;

    public static function factory(array $columnMap = []): self
    {
        return new self($columnMap);
    }

    public function __construct(array $columnMap)
    {
        $this->columnMap = $columnMap;
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

        rewind($resource);

        while (($line = fgets($resource)) !== false) {

            // Remove the trailing EOL character included by fgets() so we're only dealing with data
            $line  = preg_replace('/' . PHP_EOL . '$/', '', $line);
            $parts = [];

            //  Break the string into chunks of varying size
            foreach ($this->columnMap as $length) {
                $parts[] = substr($line, 0, $length);
                $line    = substr($line, $length);
            }

            $collection->add($parts);
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
