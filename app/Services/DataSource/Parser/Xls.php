<?php

namespace App\Services\DataSource\Parser;

use App\Services\DataSource\Interfaces\Parser;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet;

final class Xls implements Parser
{
    const MIMES = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public static function factory(): self
    {
        return new self();
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

        $file = stream_get_meta_data($resource)['uri'];

        $reader = PhpSpreadsheet\IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($file);
        $worksheet   = $spreadsheet->getSheet(0);
        $rows        = $worksheet->toArray();

        array_shift($rows);

        return new Collection($rows);
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
