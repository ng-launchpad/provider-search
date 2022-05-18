<?php

namespace App\Services\DataSource\Mapper;

use App\Models\Provider;
use App\Models\State;
use App\Services\DataSource\Mapper;

final class Hch extends Mapper
{
    const COL_INFORMATION_TYPE_CODE                   = 0;
    const COL_PIN                                     = 1;
    const COL_PROVIDER_LAST_NAME                      = 2;
    const COL_PROVIDER_FIRST_NAME                     = 3;
    const COL_PROVIDER_MIDDLE_INITIAL                 = 4;
    const COL_PROVIDER_TYPE                           = 5;
    const COL_PROVIDER_TAX_ID_NUMBER                  = 6;
    const COL_TIN_OWNER_NAME                          = 7;
    const COL_SERVICE_LOCATION_LINE_1                 = 8;
    const COL_SERVICE_LOCATION_LINE_2                 = 9;
    const COL_SERVICE_LOCATION_CITY                   = 10;
    const COL_SERVICE_LOCATION_STATE                  = 11;
    const COL_SERVICE_LOCATION_ZIP_CODE               = 12;
    const COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER   = 13;
    const COL_BILLING_ADDRESS_STREET_LINE_1           = 14;
    const COL_BILLING_ADDRESS_STREET_LINE_2           = 15;
    const COL_BILLING_CITY                            = 16;
    const COL_BILLING_STATE                           = 17;
    const COL_BILLING_ADDRESS_ZIP_CODE                = 18;
    const COL_BILLING_ADDRESS_PHONE_NUMBER            = 19;
    const COL_PRIMARY_PROVIDER_SPECIALTY              = 20;
    const COL_NETWORK_NAME                            = 21;
    const COL_NPI                                     = 22;
    const COL_TAXONOMYCODE                            = 23;
    const COL_FOREIGN_LANGUAGE1                       = 24;
    const COL_FOREIGN_LANGUAGE2                       = 25;
    const COL_FOREIGN_LANGUAGE3                       = 26;
    const COL_FOREIGN_LANGUAGE4                       = 27;
    const COL_FOREIGN_LANGUAGE5                       = 28;
    const COL_SERVICE_LOCATION_OFFICE_HOURS_MONDAY    = 29;
    const COL_SERVICE_LOCATION_OFFICE_HOURS_TUESDAY   = 30;
    const COL_SERVICE_LOCATION_OFFICE_HOURS_WEDNESDAY = 31;
    const COL_SERVICE_LOCATION_OFFICE_HOURS_THURSDAY  = 32;
    const COL_SERVICE_LOCATION_OFFICE_HOURS_FRIDAY    = 33;
    const COL_SERVICE_LOCATION_OFFICE_HOURS_SATURDAY  = 34;
    const COL_SERVICE_LOCATION_OFFICE_HOURS_SUNDAY    = 35;
    const COL_ACCEPTING_NEW_PATIENTS_IND              = 36;
    const COL_MEDICAL_GROUP_NAME                      = 37;
    const COL_HOSPITAL_AFFILIATION_1                  = 38;
    const COL_HOSPITAL_AFFILIATION_2                  = 39;
    const COL_HOSPITAL_AFFILIATION_3                  = 40;
    const COL_HOSPITAL_AFFILIATION_4                  = 41;
    const COL_HOSPITAL_AFFILIATION_5                  = 42;
    const COL_PROVIDER_GENDER                         = 43;
    const COL_SPECIALTY_2                             = 44;
    const COL_SPECIALTY_3                             = 45;

    public function skipRow(array $row): bool
    {
        if ($row[self::COL_SERVICE_LOCATION_STATE] !== $this->texas->code) {
            return true;
        }

        return false;
    }

    protected function getLanguageKeys(): array
    {
        return [
            self::COL_FOREIGN_LANGUAGE1,
            self::COL_FOREIGN_LANGUAGE2,
            self::COL_FOREIGN_LANGUAGE3,
            self::COL_FOREIGN_LANGUAGE4,
            self::COL_FOREIGN_LANGUAGE5,
        ];
    }

    protected function getLocationKeys(): array
    {
        return [
            'label'            => self::COL_MEDICAL_GROUP_NAME,
            'address_line_1'   => self::COL_SERVICE_LOCATION_LINE_1,
            'address_line_2'   => self::COL_SERVICE_LOCATION_LINE_2,
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
        return [
            self::COL_PRIMARY_PROVIDER_SPECIALTY,
            self::COL_SPECIALTY_2,
            self::COL_SPECIALTY_3,
        ];
    }

    protected function getHospitalKeys(): array
    {
        return [
            self::COL_HOSPITAL_AFFILIATION_1,
            self::COL_HOSPITAL_AFFILIATION_2,
            self::COL_HOSPITAL_AFFILIATION_3,
            self::COL_HOSPITAL_AFFILIATION_4,
            self::COL_HOSPITAL_AFFILIATION_5,
        ];
    }

    protected function getProviderKeys(): array
    {
        return [
            'label'                     => function (array $item) {
                return $this->isFacility($item)
                    ? trim($item[self::COL_MEDICAL_GROUP_NAME])
                    : trim(sprintf(
                        '%s%s%s',
                        $item[self::COL_PROVIDER_FIRST_NAME],
                        $item[self::COL_PROVIDER_MIDDLE_INITIAL]
                            ? ' ' . $item[self::COL_PROVIDER_MIDDLE_INITIAL] . '. '
                            : ' ',
                        $item[self::COL_PROVIDER_LAST_NAME],
                    ));
            },
            'type'                      => function ($item) {
                return $this->isFacility($item)
                    ? $item[self::COL_PRIMARY_PROVIDER_SPECIALTY]
                    : Mapper\Hch\TypeMap::lookup($item[self::COL_PROVIDER_TYPE]);
            },
            'npi'                       => (int) $this->getProviderNpiKey(),
            'degree'                    => fn() => null,
            'gender'                    => function ($item) {
                switch ($item[self::COL_PROVIDER_GENDER]) {
                    case 'M':
                        return Provider::GENDER_MALE;
                    case 'F':
                        return Provider::GENDER_FEMALE;
                    default:
                        return null;
                }
            },
            'network_id'                => fn() => null,
            'is_facility'               => function ($item) {
                return $this->isFacility($item);
            },
            'is_accepting_new_patients' => function ($item) {
                switch ($item[self::COL_ACCEPTING_NEW_PATIENTS_IND]) {
                    case 'Y':
                        return true;
                    case 'N':
                        return false;
                    default:
                        return null;
                }
            },
        ];
    }

    protected function getProviderNpiKey(): string
    {
        return (string) self::COL_NPI;
    }

    private function isFacility($item): bool
    {
        //  I for individual, N for non-individual
        return $item[self::COL_INFORMATION_TYPE_CODE] === 'N';
    }

    protected function getProviderPhoneKey(): string
    {
        return (string) self::COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER;
    }
}
