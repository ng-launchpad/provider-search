<?php

namespace App\Services\DataSource\Mapper;

use App\Models\Network;
use App\Models\Provider;
use App\Models\State;
use App\Services\DataSource\Mapper;

final class Vsp extends Mapper
{
    protected function getLanguageKeys(): array
    {
        return [
            'OFFICE LANG 1',
            'OFFICE LANG 2',
            'OFFICE LANG 3',
            'OFFICE LANG 4',
            'LANGUAGE SPOKEN 1',
            'LANGUAGE SPOKEN 2',
            'LANGUAGE SPOKEN 3',
            'LANGUAGE SPOKEN 4',
        ];
    }

    protected function getLocationKeys(): array
    {
        return [
            'label'            => 'PRACTICE NAME',
            'address_line_1'   => 'OFFICE ADDRESS',
            'address_city'     => 'OFFICE CITY',
            'address_county'   => 'COUNTY',
            'address_state_id' => function (array $item) {
                return State::query()
                    ->where('code', $item['ST'])
                    ->firstOrFail()
                    ->id;
            },
            'address_zip'      => 'ZIP9',
            'phone'            => 'OFFICE PHONE',
        ];
    }

    protected function getNetworkKey(): string
    {
        return 'PROVIDER NETWORK';
    }

    protected function getSpecialityKeys(): array
    {
        return [];
    }

    protected function getProviderKeys(): array
    {
        return [
            'label'                     => function (array $item) {
                return trim(sprintf(
                    '%s %s %s',
                    $item['DOCTOR FIRST NAME'],
                    $item['DOCTOR LAST NAME'],
                    $item['DEGREE'] ? ', ' . $item['DEGREE'] : '',
                ));
            },
            'type'                      => fn() => 'Physician',
            'npi'                       => 'NPI',
            'degree'                    => 'DEGREE',
            'gender'                    => function ($item) {
                switch ($item['GENDER']) {
                    case 'M':
                        return Provider::GENDER_MALE;
                    case 'F':
                        return Provider::GENDER_FEMALE;
                    default:
                        return null;
                }
            },
            'network_id'                => function (array $item) {
                return Network::query()
                    ->where('label', $item['PROVIDER NETWORK'])
                    ->firstOrFail()
                    ->id;
            },
            'is_facility'               => fn() => false,
            'is_accepting_new_patients' => fn() => null,
        ];
    }
}