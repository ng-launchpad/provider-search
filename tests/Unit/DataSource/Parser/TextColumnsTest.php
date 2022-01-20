<?php

namespace Tests\Unit\DataSource\Parser;

use App\Services\DataSource\Parser\TextColumns;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class TextColumnsTest extends TestCase
{
    /** @test */
    public function it_rejects_a_non_text_file()
    {
        // arrange
        $file   = tmpfile();
        $parser = TextColumns::factory();

        // assert
        $this->expectException(\InvalidArgumentException::class);

        // act
        $parser->parse($file);
    }

    /** @test */
    public function it_parses_a_text_file()
    {
        // arrange
        $columnMap = [3, 9, 4, 8];
        $data      = [
            ['FOO', 'BARBARBAR', 'FIZZ', 'BUZZBUZZ'],
            ['F  ', 'B        ', 'F   ', 'B       '],
        ];
        $file      = tmpfile();
        foreach ($data as $row) {
            fwrite($file, implode('', $row) . PHP_EOL);
        }

        $parser = TextColumns::factory($columnMap);

        // act
        $collection = $parser->parse($file);

        // assert
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals($data[0], $collection->get(0));
        $this->assertEquals($data[1], $collection->get(1));
    }
}