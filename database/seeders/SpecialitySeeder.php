<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Speciality;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            'Internal Medicine',
            'Urology',
            'Pediatrics',
            'Cardiology',
            'Genetics',
        ];

        foreach ($languages as $language) {
            Speciality::create([
                'version' => Setting::version(),
                'label'   => $language,
            ]);
        }
    }
}
