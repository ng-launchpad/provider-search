<?php

namespace Tests\Unit\DataSource\Parser;

use App\Services\DataSource\Parser\Csv;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase
{
    /** @test */
    public function it_rejects_a_non_csv_file()
    {
        // arrange
        $file   = tmpfile();
        $parser = Csv::factory();

        // assert
        $this->expectException(\InvalidArgumentException::class);

        // act
        $parser->parse($file)->current();
    }

    /** @test */
    public function it_parses_a_csv_file()
    {
        // arrange
        $data = [
            ['foo', 'bar'],
            ['fizz', 'buzz'],
        ];
        $file = tmpfile();
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        $parser = Csv::factory();

        // act
        $generator = $parser->parse($file);

        // assert
        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(2, $row);
        $this->assertEquals($data[0], $row);

        $generator->next();

        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(2, $row);
        $this->assertEquals($data[1], $row);
    }

    /** @test */
    public function it_skips_configured_rows()
    {
        // arrange
        $data = [
            ['foo', 'bar'],
            ['fizz', 'buzz'],
            ['cat', 'dog'],
        ];
        $file = tmpfile();
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        $parser = Csv::factory(2);

        // act
        $generator = $parser->parse($file);

        // assert
        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(2, $row);
        $this->assertEquals($data[2], $row);
    }
}
