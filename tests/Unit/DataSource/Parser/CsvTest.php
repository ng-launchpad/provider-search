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
        $parser->parse($file);
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
        $collection = $parser->parse($file);

        // assert
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals($data[0], $collection->get(0));
        $this->assertEquals($data[1], $collection->get(1));
    }
}
