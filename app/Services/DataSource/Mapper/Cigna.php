<?php

namespace App\Services\DataSource\Mapper;

use App\Models\State;
use App\Services\DataSource\Mapper;

final class Cigna extends Mapper
{
    const COL_X                                     = 0;
    const COL_NPI                                   = 1;
    const COL_SERVICE_LOCATION_LINE_1               = 3;
    const COL_SERVICE_LOCATION_CITY                 = 5;
    const COL_SERVICE_LOCATION_STATE                = 6;
    const COL_SERVICE_LOCATION_ZIP_CODE             = 7;
    const COL_SERVICE_LOCATION_COUNTRY              = 8;
    const COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER = 9;

    protected function getLanguageKeys(): array
    {
        return [];
    }

    protected function getLocationKeys(): array
    {
        return [
            'label'            => self::COL_X,
            'address_line_1'   => self::COL_SERVICE_LOCATION_LINE_1,
            'address_city'     => self::COL_SERVICE_LOCATION_CITY,
            'address_state_id' => function (array $item) {
                return State::query()
                    ->where('code', $item[self::COL_SERVICE_LOCATION_STATE])
                    ->firstOrFail()
                    ->id;
            },
            'address_zip'      => self::COL_SERVICE_LOCATION_ZIP_CODE,
            'phone'            => self::COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER,
        ];
    }

    protected function getSpecialityKeys(): array
    {
        return [];
    }

    protected function getHospitalKeys(): array
    {
        return [];
    }

    protected function getProviderKeys(): array
    {
        return [
            'label'       => self::COL_X,
            'type'        => fn() => 'Pharmacy',
            'npi'         => self::COL_NPI,
            'is_facility' => fn() => true,
        ];
    }
}
