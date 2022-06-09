<?php

namespace Database\Factories;

use App\Models\Network;
use App\Models\Provider;
use App\Models\Setting;
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
        $gender = $this->faker->optional()->randomElement([Provider::GENDER_MALE, Provider::GENDER_FEMALE]);

        return [
            'version'                   => Setting::version(),
            'label'                     => $this->faker->firstName(strtolower($gender)) . ' ' . $this->faker->lastName(),
            'type'                      => $this->faker->randomElement(['Physician', 'Doctor', 'Nurse']),
            'npi'                       => $this->faker->unique()->numerify('#########'),
            'degree'                    => $this->faker->optional()->randomElement(['MD', 'DO', 'OD']),
            'website'                   => $this->faker->optional()->domainName(),
            'gender'                    => $gender,
            'is_facility'               => false,
            'is_accepting_new_patients' => $this->faker->boolean(),
            'network_id'                => Network::inRandomOrder()->first(),
        ];
    }

    /**
     * Indicate a facility provider
     */
    public function facility()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_facility' => true,
                'label'       => $this->faker->company(),
                'type'        => $this->faker->randomElement(['Hospital', 'Children\'s Hospital', 'Acute Short Term Hospital']),
                'degree'      => null,
                'gender'      => null
            ];
        });
    }
}
