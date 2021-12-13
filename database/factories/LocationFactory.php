<?php

namespace Database\Factories;

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
        return [
            'label'            => $faker->company(),
            'type'             => $this->faker->randomElement([
                'Acute Short Term Hospital',
                'Accident and Emergency',
                'Community Hospital',
            ]),
            'address_line_1'   => $faker->streetAddress(),
            'address_city'     => $faker->city(),
            'address_county'   => null,
            'address_state_id' => State::query()->inRandomOrder()->first(),
            'address_zip'      => $faker->postcode(),
            'phone'            => $faker->phoneNumber(),
        ];
    }
}
