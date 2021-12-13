<?php

namespace Database\Factories;

use App\Models\Network;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isFacility = $this->faker->boolean();
        $gender     = $this->faker->randomElement([Provider::GENDER_MALE, Provider::GENDER_FEMALE]);

        return [
            'label'                     => $isFacility ? $this->faker->company() : $this->faker->firstName($gender) . ' ' . $this->faker->lastName(),
            'npi'                       => $this->faker->unique()->numerify('#########'),
            'phone'                     => $this->faker->optional()->phoneNumber(),
            'degree'                    => $this->faker->optional()->randomElement(['MD', 'DO', 'OD',]),
            'website'                   => $this->faker->optional()->domainName(),
            'gender'                    => $gender,
            'network_id'                => Network::query()->inRandomOrder()->first(),
            'is_facility'               => $isFacility,
            'is_accepting_new_patients' => $this->faker->boolean(),
        ];
    }
}
