<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            [
                'label' => 'Alabama',
                'code'  => 'AL',
            ],
            [
                'label' => 'Alaska',
                'code'  => 'AK',
            ],
            [
                'label' => 'Arizona',
                'code'  => 'AZ',
            ],
            [
                'label' => 'Arkansas',
                'code'  => 'AR',
            ],
            [
                'label' => 'California',
                'code'  => 'CA',
            ],
            [
                'label' => 'Colorado',
                'code'  => 'CO',
            ],
            [
                'label' => 'Connecticut',
                'code'  => 'CT',
            ],
            [
                'label' => 'Delaware',
                'code'  => 'DE',
            ],
            [
                'label' => 'District of Columbia',
                'code'  => 'DC',
            ],
            [
                'label' => 'Florida',
                'code'  => 'FL',
            ],
            [
                'label' => 'Georgia',
                'code'  => 'GA',
            ],
            [
                'label' => 'Hawaii',
                'code'  => 'HI',
            ],
            [
                'label' => 'Idaho',
                'code'  => 'ID',
            ],
            [
                'label' => 'Illinois',
                'code'  => 'IL',
            ],
            [
                'label' => 'Indiana',
                'code'  => 'IN',
            ],
            [
                'label' => 'Iowa',
                'code'  => 'IA',
            ],
            [
                'label' => 'Kansas',
                'code'  => 'KS',
            ],
            [
                'label' => 'Kentucky',
                'code'  => 'KY',
            ],
            [
                'label' => 'Louisiana',
                'code'  => 'LA',
            ],
            [
                'label' => 'Maine',
                'code'  => 'ME',
            ],
            [
                'label' => 'Maryland',
                'code'  => 'MD',
            ],
            [
                'label' => 'Massachusetts',
                'code'  => 'MA',
            ],
            [
                'label' => 'Michigan',
                'code'  => 'MI',
            ],
            [
                'label' => 'Minnesota',
                'code'  => 'MN',
            ],
            [
                'label' => 'Mississippi',
                'code'  => 'MS',
            ],
            [
                'label' => 'Missouri',
                'code'  => 'MO',
            ],
            [
                'label' => 'Montana',
                'code'  => 'MT',
            ],
            [
                'label' => 'Nebraska',
                'code'  => 'NE',
            ],
            [
                'label' => 'Nevada',
                'code'  => 'NV',
            ],
            [
                'label' => 'New Hampshire',
                'code'  => 'NH',
            ],
            [
                'label' => 'New Jersey',
                'code'  => 'NJ',
            ],
            [
                'label' => 'New Mexico',
                'code'  => 'NM',
            ],
            [
                'label' => 'New York',
                'code'  => 'NY',
            ],
            [
                'label' => 'North Carolina',
                'code'  => 'NC',
            ],
            [
                'label' => 'North Dakota',
                'code'  => 'ND',
            ],
            [
                'label' => 'Ohio',
                'code'  => 'OH',
            ],
            [
                'label' => 'Oklahoma',
                'code'  => 'OK',
            ],
            [
                'label' => 'Oregon',
                'code'  => 'OR',
            ],
            [
                'label' => 'Pennsylvania',
                'code'  => 'PA',
            ],
            [
                'label' => 'Rhode Island',
                'code'  => 'RI',
            ],
            [
                'label' => 'South Carolina',
                'code'  => 'SC',
            ],
            [
                'label' => 'South Dakota',
                'code'  => 'SD',
            ],
            [
                'label' => 'Tennessee',
                'code'  => 'TN',
            ],
            [
                'label' => 'Texas',
                'code'  => 'TX',
            ],
            [
                'label' => 'Utah',
                'code'  => 'UT',
            ],
            [
                'label' => 'Vermont',
                'code'  => 'VT',
            ],
            [
                'label' => 'Virginia',
                'code'  => 'VA',
            ],
            [
                'label' => 'Washington',
                'code'  => 'WA',
            ],
            [
                'label' => 'West Virginia',
                'code'  => 'WV',
            ],
            [
                'label' => 'Wisconsin',
                'code'  => 'WI',
            ],
            [
                'label' => 'Wyoming',
                'code'  => 'WY',
            ],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
