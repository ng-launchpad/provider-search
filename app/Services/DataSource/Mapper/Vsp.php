<?php

namespace App\Services\DataSource\Mapper;

use App\Models\Provider;
use App\Models\State;
use App\Services\DataSource\Mapper;

final class Vsp extends Mapper
{
    const COL_DOCTOR_LAST_NAME    = 0;
    const COL_DOCTOR_FIRST_NAME   = 1;
    const COL_MI                  = 2;
    const COL_DEGREE              = 3;
    const COL_FULL_NAME           = 4;
    const COL_GENDER              = 5;
    const COL_DOB                 = 6;
    const COL_NPI                 = 7;
    const COL_LICENSE_NO          = 8;
    const COL_LICENSE_ST          = 9;
    const COL_BOARD_CERTIFIED     = 10;
    const COL_BOARD_AWARD         = 11;
    const COL_PRACTICE_NAME       = 12;
    const COL_OFFICE_ADDRESS      = 13;
    const COL_OFFICE_CITY         = 14;
    const COL_ST                  = 15;
    const COL_ZIP9                = 16;
    const COL_OFFICE_PHONE        = 17;
    const COL_COUNTY              = 18;
    const COL_HANDICAP_ACCESS     = 19;
    const COL_BILLING_NAME        = 20;
    const COL_NPI_TYPE_2          = 21;
    const COL_TAXONOMY            = 22;
    const COL_OFFICE_MON_HOURS    = 23;
    const COL_OFFICE_TUE_HOURS    = 24;
    const COL_OFFICE_WED_HOURS    = 25;
    const COL_OFFICE_THU_HOURS    = 26;
    const COL_OFFICE_FRI_HOURS    = 27;
    const COL_OFFICE_SAT_HOURS    = 28;
    const COL_OFFICE_SUN_HOURS    = 29;
    const COL_OFFICE_LANG_1       = 30;
    const COL_OFFICE_LANG_2       = 31;
    const COL_OFFICE_LANG_3       = 32;
    const COL_OFFICE_LANG_4       = 33;
    const COL_LANGUAGE_SPOKEN_1   = 34;
    const COL_LANGUAGE_SPOKEN_2   = 35;
    const COL_LANGUAGE_SPOKEN_3   = 36;
    const COL_LANGUAGE_SPOKEN_4   = 37;
    const COL_MEDICAID_ID         = 38;
    const COL_MEDICARE_NUMBER     = 39;
    const COL_LAST_COMMITTEE_DT   = 41;
    const COL_STATUS_AT_COMMITTEE = 42;
    const COL_RECREDENTIAL_DT     = 43;
    const COL_DIRECTORY_PRINT     = 44;
    const COL_PROVIDER_NETWORK    = 45;
    const COL_START_DATE          = 46;
    const COL_TAX_ID              = 47;
    const COL_FAX_NUMBER          = 48;

    public function skipRow(array $row): bool
    {
        if ($row[self::COL_ST] !== $this->texas->code) {
            return true;
        }

        return false;
    }

    protected function getLanguageKeys(): array
    {
        return [
            self::COL_LANGUAGE_SPOKEN_1,
            self::COL_LANGUAGE_SPOKEN_2,
            self::COL_LANGUAGE_SPOKEN_3,
            self::COL_LANGUAGE_SPOKEN_4,
        ];
    }

    protected function getLocationKeys(): array
    {
        return [
            'label'            => self::COL_PRACTICE_NAME,
            'address_line_1'   => self::COL_OFFICE_ADDRESS,
            'address_city'     => self::COL_OFFICE_CITY,
            'address_county'   => self::COL_COUNTY,
            'address_state_id' => function (array $item) {
                return State::findByCodeOrFail($item[self::COL_ST])->id;
            },
            'address_zip'      => self::COL_ZIP9,
            'phone'            => self::COL_OFFICE_PHONE,
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
            'label'                     => function (array $item) {
                return trim(sprintf(
                    '%s %s%s',
                    $item[self::COL_DOCTOR_FIRST_NAME],
                    $item[self::COL_DOCTOR_LAST_NAME],
                    $item[self::COL_DEGREE] ? ', ' . $item[self::COL_DEGREE] : '',
                ));
            },
            'type'                      => fn() => 'Physician',
            'npi'                       => $this->getProviderNpiKey(),
            'degree'                    => self::COL_DEGREE,
            'gender'                    => function ($item) {
                switch ($item[self::COL_GENDER]) {
                    case 'M':
                        return Provider::GENDER_MALE;
                    case 'F':
                        return Provider::GENDER_FEMALE;
                    default:
                        return null;
                }
            },
            'network_id'                => fn() => null,
            'is_facility'               => fn() => false,
            'is_accepting_new_patients' => fn() => null,
        ];
    }

    protected function getProviderNpiKey(): string
    {
        return self::COL_NPI;
    }
}
