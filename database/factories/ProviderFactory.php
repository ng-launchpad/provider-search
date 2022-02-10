<?php

namespace Database\Factories;

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
        $isFacility = $this->faker->boolean();
        $gender     = $this->faker->optional()->randomElement([Provider::GENDER_MALE, Provider::GENDER_FEMALE]);

        return [
            'version'                   => Setting::version(),
            'label'                     => $isFacility
                ? $this->faker->company()
                : $this->faker->firstName(strtolower($gender)) . ' ' . $this->faker->lastName(),
            'type'                      => $isFacility
                ? $this->faker->randomElement(['Hospital', 'Children\'s Hospital', 'Acute Short Term Hospital'])
                : $this->faker->randomElement(['Physician', 'Doctor', 'Nurse']),
            'npi'                       => $this->faker->unique()->numerify('#########'),
            'degree'                    => $isFacility
                ? null
                : $this->faker->optional()->randomElement(['MD', 'DO', 'OD']),
            'website'                   => $this->faker->optional()->domainName(),
            'gender'                    => $isFacility
                ? null
                : $gender,
            'is_facility'               => $isFacility,
            'is_accepting_new_patients' => $this->faker->boolean(),
        ];
    }
}
