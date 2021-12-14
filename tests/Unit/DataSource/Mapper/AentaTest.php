<?php

namespace Tests\Unit\DataSource\Mapper;

use App\Models\Provider;
use App\Services\DataSource\Mapper\Aenta;
use Faker\Factory;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class AentaTest extends TestCase
{
    /** @test */
    public function it_maps_the_data()
    {
        // arrange
        $data       = $this->getData();
        $collection = new Collection($data);
        $mapper     = Aenta::factory();

        // act
        $collection = $collection->map(fn(array $item) => $mapper->transform($item));

        // assert
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(1, $collection);
        $this->assertInstanceOf(Provider::class, $collection->get(0));

        //  @todo (Pablo 2021-12-10) - Add assertions for all the columns the mapper maps
        $this->assertEquals($data[0]['COLUMN NAME'], $collection->get(0)->label);
    }

    private function getData(): array
    {
        $faker = Factory::create();

        return [
            [
                'COLUMN NAME' => $faker->words(5, true),
            ],
        ];
    }
}
