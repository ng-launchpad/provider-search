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
        $parser->parse($file);
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
        $collection = $parser->parse($file);

        // assert
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals($data['A1'], $collection->get(0)[0]);
        $this->assertEquals($data['B1'], $collection->get(0)[1]);
        $this->assertEquals($data['A2'], $collection->get(1)[0]);
        $this->assertEquals($data['B2'], $collection->get(1)[1]);
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
        $collection = $parser->parse($file);

        // assert
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertEquals($data['A2'], $collection->get(0)[0]);
        $this->assertEquals($data['B2'], $collection->get(0)[1]);
        $this->assertEquals($data['A3'], $collection->get(1)[0]);
        $this->assertEquals($data['B3'], $collection->get(1)[1]);
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
