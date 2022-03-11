<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Setting;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        $data  = [
            'version'          => Setting::version(),
            'label'            => $faker->company(),
            'type'             => $this->faker->randomElement([
                'Acute Short Term Hospital',
                'Accident and Emergency',
                'Community Hospital',
                'Children\'s Hospital',
                'Hospital',
            ]),
            'address_line_1'   => $faker->streetAddress(),
            'address_city'     => $faker->city(),
            'address_county'   => null,
            'address_state_id' => State::query()->inRandomOrder()->first(),
            'address_zip'      => $faker->postcode(),
            'phone'            => $faker->phoneNumber(),
        ];

        $data['hash'] = Location::buildHashFromData((object) $data);

        return $data;
    }
}
