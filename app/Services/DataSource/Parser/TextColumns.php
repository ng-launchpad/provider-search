<?php

namespace App\Services\DataSource\Parser;

use App\Helper\BytesForHumans;
use App\Services\DataSource\Interfaces\Parser;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TextColumns implements Parser
{
    const MIMES = ['text/plain', 'application/octet-stream'];

    private int   $offset;
    private array $columnMap;

    public static function factory(int $offset = 0, array $columnMap = []): self
    {
        return new self($offset, $columnMap);
    }

    public function __construct(int $offset = 0, array $columnMap = [])
    {
        $this->offset    = $offset;
        $this->columnMap = $columnMap;
    }

    public function parse($resource, OutputInterface $output): Collection
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

        if ($output instanceof ConsoleOutputInterface) {
            $section = $output->section();
        }

        while (($line = fgets($resource)) !== false) {
            if ($i >= $this->offset) {

                // Remove the trailing EOL character included by fgets() so we're only dealing with data
                $line  = preg_replace('/' . PHP_EOL . '$/', '', $line);
                $parts = [];

                //  Break the string into chunks of varying size
                foreach ($this->columnMap as $length) {
                    $parts[] = utf8_encode(trim(substr($line, 0, $length)));
                    $line    = substr($line, $length);
                }

                $collection->add($parts);

                if (isset($section)) {
                    $section->overwrite(sprintf(
                        'Processed line %s; memory usage %s',
                        $i,
                        BytesForHumans::fromBytes(memory_get_usage())
                    ));
                }
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
