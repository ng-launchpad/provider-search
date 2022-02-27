<?php

namespace App\Services\DataSource\Parser;

use App\Helper\BytesForHumans;
use App\Services\DataSource\Interfaces\Parser;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

        while (($data = fgetcsv($resource)) !== false) {
            if ($i >= $this->offset) {
                $data = array_map('utf8_encode', $data);
                $data = array_map('trim', $data);
                $collection->add($data);

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
