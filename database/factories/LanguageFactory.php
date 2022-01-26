<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'version' => Setting::version(),
            'label'   => $this->faker->randomElement(['English', 'Spanish', 'Chinese', 'Arabic']),
        ];
    }
}
