<?php

namespace Tests\Unit\DataSource\Mapper;

use App\Models\Provider;
use App\Services\DataSource\Mapper\Vsp;
use Faker\Factory;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class VspTest extends TestCase
{
    /** @test */
    public function it_maps_the_data()
    {
        // arrange
        $data       = $this->getData();
        $collection = new Collection($data);
        $mapper     = Vsp::factory();

        // act
        $collection = $collection->map(fn(array $item) => $mapper->transform($item));

        // assert
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(1, $collection);
        $this->assertInstanceOf(Provider::class, $collection->get(0));

        //  @todo (Pablo 2021-12-10) - Add assertions for all the columns the mapper maps
        $this->assertEquals($data[0]['PRACTICE NAME'], $collection->get(0)->label);
    }

    private function getData(): array
    {
        $faker = Factory::create();

        return [
            [
                'DOCTOR LAST NAME'    => $faker->firstName,
                'DOCTOR FIRST NAME'   => $faker->lastName,
                'MI'                  => 'R',
                'DEGREE'              => 'OD',
                'FULL NAME'           => $faker->name,
                'GENDER'              => $faker->randomElement(['M', 'F', '']),
                'DOB'                 => $faker->date('Ymd'),
                'NPI'                 => $faker->numerify('##########'),
                'LICENSE NO'          => $faker->numerify('####'),
                'LICENSE ST'          => $faker->stateAbbr,
                'BOARD CERTIFIED'     => $faker->randomElement(['Y', 'N', '']),
                'BOARD AWARD'         => $faker->numerify('########'),
                'PRACTICE NAME'       => $faker->company,
                'OFFICE ADDRESS'      => $faker->streetAddress,
                'OFFICE CITY'         => $faker->city,
                'ST'                  => $faker->stateAbbr,
                'ZIP9'                => $faker->postcode,
                'OFFICE PHONE'        => $faker->phoneNumber,
                'COUNTY'              => $faker->streetName,
                'HANDICAP ACCESS'     => $faker->randomElement(['Y', 'N', '']),
                '24-HOUR ACCESS'      => $faker->randomElement(['Y', 'N', '']),
                'BILLING NAME'        => $faker->company,
                'NPI TYPE 2'          => $faker->numerify('##########'),
                'TAXONOMY'            => $faker->numerify('###W#####X'),
                'OFFICE MON HOURS'    => '09:00 - 06:00',
                'OFFICE TUE HOURS'    => '09:00 - 06:00',
                'OFFICE WED HOURS'    => '10:00 - 06:00',
                'OFFICE THU HOURS'    => '09:00 - 06:00',
                'OFFICE FRI HOURS'    => '09:00 - 05:00',
                'OFFICE SAT HOURS'    => '09:00 - 01:00',
                'OFFICE SUN HOURS'    => '',
                'OFFICE LANG 1'       => $faker->randomElement(['Spanish', 'Cantonese', 'Vietnamese', '']),
                'OFFICE LANG 2'       => $faker->randomElement(['Spanish', 'Cantonese', 'Vietnamese', '']),
                'OFFICE LANG 3'       => $faker->randomElement(['Spanish', 'Cantonese', 'Vietnamese', '']),
                'OFFICE LANG 4'       => $faker->randomElement(['Spanish', 'Cantonese', 'Vietnamese', '']),
                'LANGUAGE SPOKEN 1'   => $faker->randomElement(['Spanish', 'Cantonese', 'Vietnamese', '']),
                'LANGUAGE SPOKEN 2'   => $faker->randomElement(['Spanish', 'Cantonese', 'Vietnamese', '']),
                'LANGUAGE SPOKEN 3'   => $faker->randomElement(['Spanish', 'Cantonese', 'Vietnamese', '']),
                'LANGUAGE SPOKEN 4'   => $faker->randomElement(['Spanish', 'Cantonese', 'Vietnamese', '']),
                'MEDICAID ID'         => '',
                'MEDICARE NUMBER'     => $faker->numerify('##########'),
                'LAST COMMITTEE DT'   => $faker->date('Ymd'),
                'STATUS AT COMMITTEE' => $faker->randomElement(['Initial Credentialing', 'Recredentialing']),
                'RECREDENTIAL DT'     => $faker->date('Ymd'),
                'DIRECTORY PRINT'     => $faker->randomElement(['Y', 'N', '']),
                'PROVIDER NETWORK'    => $faker->randomElement(['Choice']),
                'START DATE'          => $faker->date('Ymd'),
            ],
        ];
    }
}
