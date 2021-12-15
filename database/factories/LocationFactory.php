<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
                'Children\'s Hospital',
                'Hospital',
            ]),
            'address_line_1'   => $faker->streetAddress(),
            'address_city'     => $faker->city(),
            'address_county'   => null,
            // Always seed for Texas as, currently, that's the only supported state
            //'address_state_id' => State::query()->inRandomOrder()->first(),
            'address_state_id' => State::findByCodeOrFail('TX'),
            'address_zip'      => $faker->postcode(),
            'phone'            => $faker->phoneNumber(),
        ];
    }
}
