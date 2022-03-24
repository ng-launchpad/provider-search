<?php

namespace App\Services\DataSource\Mapper;

use App\Models\State;
use App\Services\DataSource\Mapper;

final class Cigna extends Mapper
{
    const COL_PHARMACY_NAME                         = 0;
    const COL_NPI                                   = 1;
    const COL_SERVICE_LOCATION_LINE_1               = 3;
    const COL_SERVICE_LOCATION_CITY                 = 5;
    const COL_SERVICE_LOCATION_STATE                = 6;
    const COL_SERVICE_LOCATION_ZIP_CODE             = 7;
    const COL_SERVICE_LOCATION_COUNTRY              = 8;
    const COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER = 9;

    public function skipRow(array $row): bool
    {
        if ($row[self::COL_SERVICE_LOCATION_STATE] !== $this->texas->code) {
            return true;
        }

        return false;
    }

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
                return State::findByCodeOrFail($item[self::COL_SERVICE_LOCATION_STATE])->id;
            },
            'address_zip'      => self::COL_SERVICE_LOCATION_ZIP_CODE,
            'phone'            => $this->getProviderPhoneKey(),
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
            'label'       => self::COL_PHARMACY_NAME,
            'type'        => fn() => 'Pharmacy',
            'npi'         => $this->getProviderNpiKey(),
            'is_facility' => fn() => true,
        ];
    }

    protected function getProviderNpiKey(): string
    {
        return self::COL_NPI;
    }

    protected function getProviderPhoneKey(): string
    {
        return (string) self::COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER;
    }
}
