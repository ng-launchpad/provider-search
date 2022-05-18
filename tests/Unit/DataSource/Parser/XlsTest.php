<?php

namespace Tests\Unit\DataSource\Parser;

use App\Services\DataSource\Parser\Xls;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet;
use PHPUnit\Framework\TestCase;

class XlsTest extends TestCase
{
    /** @test */
    public function it_rejects_a_non_xls_file()
    {
        // arrange
        $file   = tmpfile();
        $parser = Xls::factory();

        // assert
        $this->expectException(\InvalidArgumentException::class);

        // act
        $parser->parse($file)->current();
    }

    /** @test */
    public function it_parses_an_xls_file()
    {
        // arrange
        $data   = [
            'A1' => 'Fizz',
            'B1' => 'Buzz',
            'A2' => 'Fizz',
            'B2' => 'Buzz',
        ];
        $file   = $this->createXls($data);
        $parser = Xls::factory();

        // act
        $generator = $parser->parse($file);

        // assert
        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(2, $row);
        $this->assertEquals($data['A1'], $row[0]);
        $this->assertEquals($data['B1'], $row[1]);

        $generator->next();

        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(2, $row);
        $this->assertEquals($data['A2'], $row[0]);
        $this->assertEquals($data['B2'], $row[1]);
    }

    /** @test */
    public function it_skips_configured_rows()
    {
        // arrange
        $data   = [
            'A1' => 'Header Row',
            'B1' => 'Header Row',
            'A2' => 'Fizz',
            'B2' => 'Buzz',
            'A3' => 'Fizz',
            'B3' => 'Buzz',
        ];
        $file   = $this->createXls($data);
        $parser = Xls::factory(1);

        // act
        $generator = $parser->parse($file);

        // assert
        $row = $generator->current();
        $this->assertIsArray($row);
        $this->assertCount(2, $row);
        $this->assertEquals($data['A3'], $row[0]);
        $this->assertEquals($data['B3'], $row[1]);
    }

    private function createXls(array $data)
    {
        $file = tmpfile();

        $spreadsheet = new PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        foreach ($data as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $writer = new PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($file);

        return $file;
    }
}
