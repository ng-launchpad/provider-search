<?php

namespace App\Services\DataSource\Parser;

use App\Services\DataSource\Interfaces\Parser;
use PhpOffice\PhpSpreadsheet;

final class Xls implements Parser
{
    const MIMES = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    private int $offset;

    public static function factory(int $offset = 0): self
    {
        return new self($offset);
    }

    public function __construct(int $offset = 0)
    {
        $this->offset = $offset;
    }

    public function parse($resource): \Generator
    {
        if (!$this->isValidMime($resource)) {
            throw new \InvalidArgumentException(sprintf(
                'invalid file type, expected `%s`, got %s',
                implode(', ', self::MIMES),
                $this->getMime($resource)
            ));
        }

        $file = stream_get_meta_data($resource)['uri'];

        $reader = PhpSpreadsheet\IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($file);
        $worksheet   = $spreadsheet->getSheet(0);
        $rows        = $worksheet->toArray();

        for ($i = 0; $i < $this->offset; $i++) {
            array_shift($rows);
        }

        foreach ($rows as $row) {

            $row = array_map('trim', $row);
            $row = array_map('utf8_encode', $row);
            yield $row;
        }
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
