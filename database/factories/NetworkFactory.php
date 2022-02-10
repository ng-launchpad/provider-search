<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NetworkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'label'           => ucwords($this->faker->words(3, true)),
            'search_label'    => ucwords($this->faker->words(3, true)),
            'search_sublabel' => ucwords($this->faker->words(3, true)),
            'network_label'   => ucwords($this->faker->words(3, true)),
            'browse_label'    => ucwords($this->faker->words(3, true)),
            'config_key'      => '',
        ];
    }
}
