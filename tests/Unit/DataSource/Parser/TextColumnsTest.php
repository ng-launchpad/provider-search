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
        $parser->parse($file)->current();
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

        $parser = TextColumns::factory(0, $columnMap);

        // act
        $generator = $parser->parse($file);

        // assert
        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(4, $row);
        $this->assertEquals(array_map('trim', $data[0]), $row);

        $generator->next();

        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(4, $row);
        $this->assertEquals(array_map('trim', $data[1]), $row);
    }

    /** @test */
    public function it_skips_configured_rows()
    {
        // arrange
        $columnMap = [3, 9, 4, 8];
        $data      = [
            ['FOO', 'BARBARBAR', 'FIZZ', 'BUZZBUZZ'],
            ['F  ', 'B        ', 'F   ', 'B       '],
            ['CAT', 'DOGDOGDOG', 'CATS', 'DOGSDOGS'],
        ];
        $file      = tmpfile();
        foreach ($data as $row) {
            fwrite($file, implode('', $row) . PHP_EOL);
        }

        $parser = TextColumns::factory(2, $columnMap);

        // act
        $generator = $parser->parse($file);

        // assert
        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(4, $row);
        $this->assertEquals($data[2], $row);
    }
}
