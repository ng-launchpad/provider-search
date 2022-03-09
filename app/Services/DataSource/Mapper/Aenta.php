<?php

namespace App\Services\DataSource\Mapper;

use App\Models\Provider;
use App\Models\State;
use App\Services\DataSource\Mapper;

final class Aenta extends Mapper
{
    /**
     * Define the column index and length for each column in the data set
     * - COL_ = the column index
     * - LEN_ = the column length
     */

    const COL_INFORMATION_TYPE_CODE = 0;
    const LEN_INFORMATION_TYPE_CODE = 1;

    const COL_PROVIDER_CLASS_CODE = 1;
    const LEN_PROVIDER_CLASS_CODE = 3;

    const COL_PIN = 2;
    const LEN_PIN = 10;

    const COL_PIN_SUFFIX = 3;
    const LEN_PIN_SUFFIX = 3;

    const COL_PROVIDER_LAST_NAME = 4;
    const LEN_PROVIDER_LAST_NAME = 50;

    const COL_PROVIDER_FIRST_NAME = 5;
    const LEN_PROVIDER_FIRST_NAME = 25;

    const COL_PROVIDER_MIDDLE_INITIAL = 6;
    const LEN_PROVIDER_MIDDLE_INITIAL = 1;

    const COL_PROVIDER_TYPE = 7;
    const LEN_PROVIDER_TYPE = 3;

    const COL_TIN_FORMAT_CODE = 8;
    const LEN_TIN_FORMAT_CODE = 1;

    const COL_PROVIDER_TAX_ID_NUMBER = 9;
    const LEN_PROVIDER_TAX_ID_NUMBER = 9;

    const COL_TIN_OWNER_NAME = 10;
    const LEN_TIN_OWNER_NAME = 70;

    const COL_PROVIDER_DEGREE = 11;
    const LEN_PROVIDER_DEGREE = 5;

    const COL_SERVICE_LOCATION_LINE_1 = 12;
    const LEN_SERVICE_LOCATION_LINE_1 = 40;

    const COL_SERVICE_LOCATION_LINE_2 = 13;
    const LEN_SERVICE_LOCATION_LINE_2 = 40;

    const COL_SERVICE_LOCATION_CITY = 14;
    const LEN_SERVICE_LOCATION_CITY = 24;

    const COL_SERVICE_LOCATION_STATE = 15;
    const LEN_SERVICE_LOCATION_STATE = 2;

    const COL_SERVICE_LOCATION_ZIP_CODE = 16;
    const LEN_SERVICE_LOCATION_ZIP_CODE = 5;

    const COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER = 17;
    const LEN_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER = 20;

    const COL_BILLING_ADDRESS_STREET_LINE_1 = 18;
    const LEN_BILLING_ADDRESS_STREET_LINE_1 = 40;

    const COL_BILLING_ADDRESS_STREET_LINE_2 = 19;
    const LEN_BILLING_ADDRESS_STREET_LINE_2 = 40;

    const COL_BILLING_CITY = 20;
    const LEN_BILLING_CITY = 24;

    const COL_BILLING_STATE = 21;
    const LEN_BILLING_STATE = 2;

    const COL_BILLING_ADDRESS_ZIP_CODE = 22;
    const LEN_BILLING_ADDRESS_ZIP_CODE = 5;

    const COL_BILLING_ADDRESS_PHONE_NUMBER = 23;
    const LEN_BILLING_ADDRESS_PHONE_NUMBER = 20;

    const COL_PRIMARY_PROVIDER_SPECIALTY = 24;
    const LEN_PRIMARY_PROVIDER_SPECIALTY = 5;

    const COL_PROVIDER_ORIGINAL_EFFECTIVE_DATE = 25;
    const LEN_PROVIDER_ORIGINAL_EFFECTIVE_DATE = 10;

    const COL_PROVIDER_TERMINATION_DATE = 26;
    const LEN_PROVIDER_TERMINATION_DATE = 10;

    const COL_SOCIAL_SECURITY_NUMBER = 27;
    const LEN_SOCIAL_SECURITY_NUMBER = 9;

    const COL_STATE_SURCHARGE = 28;
    const LEN_STATE_SURCHARGE = 2;

    const COL_SURCHARGE_EFFECTIVE_DATE = 29;
    const LEN_SURCHARGE_EFFECTIVE_DATE = 10;

    const COL_SURCHARGE_TERMINATION_DATE = 30;
    const LEN_SURCHARGE_TERMINATION_DATE = 10;

    const COL_NETWORK_ID = 31;
    const LEN_NETWORK_ID = 5;

    const COL_NETWORK_TYPE = 32;
    const LEN_NETWORK_TYPE = 3;

    const COL_NETWORK_NAME = 33;
    const LEN_NETWORK_NAME = 25;

    const COL_PRODUCT_CODE = 34;
    const LEN_PRODUCT_CODE = 7;

    const COL_ALTERNATE_PRODUCT = 35;
    const LEN_ALTERNATE_PRODUCT = 7;

    const COL_ADD_CHANGE_INDICATOR = 36;
    const LEN_ADD_CHANGE_INDICATOR = 1;

    const COL_INACTIVATION_REASON_CODE = 37;
    const LEN_INACTIVATION_REASON_CODE = 20;

    const COL_REPLACED_BY_PIN = 38;
    const LEN_REPLACED_BY_PIN = 10;

    const COL_REJECTION_CODE = 39;
    const LEN_REJECTION_CODE = 3;

    const COL_NPI = 40;
    const LEN_NPI = 10;

    const COL_FILLER = 41;
    const LEN_FILLER = 35;

    const COL_TAXONOMYCODE = 42;
    const LEN_TAXONOMYCODE = 15;

    const COL_NPI_2 = 43;
    const LEN_NPI_2 = 10;

    const COL_LICENSE_NUMBER = 44;
    const LEN_LICENSE_NUMBER = 20;

    const COL_LICENSE_STATE = 45;
    const LEN_LICENSE_STATE = 2;

    const COL_ECP_FEDERALLY_QUALIFIED_HEALTH_CARE = 46;
    const LEN_ECP_FEDERALLY_QUALIFIED_HEALTH_CARE = 1;

    const COL_ECP_RYAN_WHITE = 47;
    const LEN_ECP_RYAN_WHITE = 1;

    const COL_ECP_INDIAN_PROVIDER = 48;
    const LEN_ECP_INDIAN_PROVIDER = 1;

    const COL_ECP_FAMILY_PLANNING = 49;
    const LEN_ECP_FAMILY_PLANNING = 1;

    const COL_ECP_HOSPITAL = 50;
    const LEN_ECP_HOSPITAL = 1;

    const COL_ECP_OTHER = 51;
    const LEN_ECP_OTHER = 1;

    const COL_FOREIGN_LANGUAGE1 = 52;
    const LEN_FOREIGN_LANGUAGE1 = 25;

    const COL_FOREIGN_LANGUAGE2 = 53;
    const LEN_FOREIGN_LANGUAGE2 = 25;

    const COL_FOREIGN_LANGUAGE3 = 54;
    const LEN_FOREIGN_LANGUAGE3 = 25;

    const COL_FOREIGN_LANGUAGE4 = 55;
    const LEN_FOREIGN_LANGUAGE4 = 25;

    const COL_FOREIGN_LANGUAGE5 = 56;
    const LEN_FOREIGN_LANGUAGE5 = 25;

    const COL_SERVICE_LOCATION_DIRECTORY_INDICATOR = 57;
    const LEN_SERVICE_LOCATION_DIRECTORY_INDICATOR = 1;

    const COL_SERVICE_LOCATION_HANDICAP_INDICATOR = 58;
    const LEN_SERVICE_LOCATION_HANDICAP_INDICATOR = 1;

    const COL_SERVICE_LOCATION_OFFICE_HOURS_MONDAY = 59;
    const LEN_SERVICE_LOCATION_OFFICE_HOURS_MONDAY = 25;

    const COL_SERVICE_LOCATION_OFFICE_HOURS_TUESDAY = 60;
    const LEN_SERVICE_LOCATION_OFFICE_HOURS_TUESDAY = 25;

    const COL_SERVICE_LOCATION_OFFICE_HOURS_WEDNESDAY = 61;
    const LEN_SERVICE_LOCATION_OFFICE_HOURS_WEDNESDAY = 25;

    const COL_SERVICE_LOCATION_OFFICE_HOURS_THURSDAY = 62;
    const LEN_SERVICE_LOCATION_OFFICE_HOURS_THURSDAY = 25;

    const COL_SERVICE_LOCATION_OFFICE_HOURS_FRIDAY = 63;
    const LEN_SERVICE_LOCATION_OFFICE_HOURS_FRIDAY = 25;

    const COL_SERVICE_LOCATION_OFFICE_HOURS_SATURDAY = 64;
    const LEN_SERVICE_LOCATION_OFFICE_HOURS_SATURDAY = 25;

    const COL_SERVICE_LOCATION_OFFICE_HOURS_SUNDAY = 65;
    const LEN_SERVICE_LOCATION_OFFICE_HOURS_SUNDAY = 25;

    const COL_ACCEPTING_NEW_PATIENTS_IND = 66;
    const LEN_ACCEPTING_NEW_PATIENTS_IND = 1;

    const COL_NETWORK_TERMINATION_REASON_CODE = 67;
    const LEN_NETWORK_TERMINATION_REASON_CODE = 6;

    const COL_AFTERHOURSCARE_IND = 68;
    const LEN_AFTERHOURSCARE_IND = 1;

    const COL_IOQ_BARIATRIC = 69;
    const LEN_IOQ_BARIATRIC = 1;

    const COL_IOQ_CARDIAC_RHYTHM = 70;
    const LEN_IOQ_CARDIAC_RHYTHM = 1;

    const COL_IOQ_CARDIAC_SURGERY = 71;
    const LEN_IOQ_CARDIAC_SURGERY = 1;

    const COL_IOQ_CARDIAC_MEDICAL_INTERVENTION = 72;
    const LEN_IOQ_CARDIAC_MEDICAL_INTERVENTION = 1;

    const COL_IOQ_ORTHOPEDIC_SPINE = 73;
    const LEN_IOQ_ORTHOPEDIC_SPINE = 1;

    const COL_IOQ_ORTHOPEDIC_TOTAL_JOINT_REPLACEMENT = 74;
    const LEN_IOQ_ORTHOPEDIC_TOTAL_JOINT_REPLACEMENT = 1;

    const COL_IOE_TRANSPLANT_FACILITY = 75;
    const LEN_IOE_TRANSPLANT_FACILITY = 1;

    const COL_IOE_HOSPITAL = 76;
    const LEN_IOE_HOSPITAL = 1;

    const COL_IOE_INFERTILITY_PROGRAM = 77;
    const LEN_IOE_INFERTILITY_PROGRAM = 1;

    const COL_IOE_IVF_AFFILIATED_PHYSICIAN = 78;
    const LEN_IOE_IVF_AFFILIATED_PHYSICIAN = 1;

    const COL_PRIMARY_PROVIDER_SPECIALTY_BOARD_CERTIFICATION_YEAR = 79;
    const LEN_PRIMARY_PROVIDER_SPECIALTY_BOARD_CERTIFICATION_YEAR = 4;

    const COL_MEDICAL_GROUP_NAME = 80;
    const LEN_MEDICAL_GROUP_NAME = 100;

    const COL_HOSPITAL_AFFILIATION_1 = 81;
    const LEN_HOSPITAL_AFFILIATION_1 = 100;

    const COL_HOSPITAL_AFFILIATION_2 = 82;
    const LEN_HOSPITAL_AFFILIATION_2 = 100;

    const COL_HOSPITAL_AFFILIATION_3 = 83;
    const LEN_HOSPITAL_AFFILIATION_3 = 100;

    const COL_HOSPITAL_AFFILIATION_4 = 84;
    const LEN_HOSPITAL_AFFILIATION_4 = 100;

    const COL_HOSPITAL_AFFILIATION_5 = 85;
    const LEN_HOSPITAL_AFFILIATION_5 = 100;

    const COL_PROVIDER_GENDER = 86;
    const LEN_PROVIDER_GENDER = 1;

    const COL_EDUCATION_SCHOOL_NAME = 87;
    const LEN_EDUCATION_SCHOOL_NAME = 50;

    const COL_EDUCATION_SCHOOL_GRADUATION_YEAR = 88;
    const LEN_EDUCATION_SCHOOL_GRADUATION_YEAR = 4;

    const COL_AEXCEL_HIGH_PERFORMANCE_PHYSICIAN_NETWORK = 89;
    const LEN_AEXCEL_HIGH_PERFORMANCE_PHYSICIAN_NETWORK = 1;

    const COL_SPECIALTY2 = 90;
    const LEN_SPECIALTY2 = 5;

    const COL_SPECIALTY3 = 91;
    const LEN_SPECIALTY3 = 5;

    const ADD_CHANGE_INDICATOR_ADD         = 'A';   // indicates a brand-new Pin/Suffix created.  All data elements would be returned.
    const ADD_CHANGE_INDICATOR_REINSTATE   = 'R';   // indicates a reinstatement (re-activation) of a Pin/Suffix.  All data elements would be returned.
    const ADD_CHANGE_INDICATOR_TERMINATED  = 'T';   // indicates a Pin/Suffix has been terminated. All data elements would be returned.
    const ADD_CHANGE_INDICATOR_INACTIVATED = 'I';   // indicates a Pin/Suffix Tax-Id has been inactivated (as by death or retirement). All data elements would be returned,
    const ADD_CHANGE_INDICATOR_CHANGED     = 'C';   // indicates a change(s) to the provider data.  All data elements would be returned and may be overlaid.

    public static function getColumnLengths(): array
    {
        return [
            self::LEN_INFORMATION_TYPE_CODE,
            self::LEN_PROVIDER_CLASS_CODE,
            self::LEN_PIN,
            self::LEN_PIN_SUFFIX,
            self::LEN_PROVIDER_LAST_NAME,
            self::LEN_PROVIDER_FIRST_NAME,
            self::LEN_PROVIDER_MIDDLE_INITIAL,
            self::LEN_PROVIDER_TYPE,
            self::LEN_TIN_FORMAT_CODE,
            self::LEN_PROVIDER_TAX_ID_NUMBER,
            self::LEN_TIN_OWNER_NAME,
            self::LEN_PROVIDER_DEGREE,
            self::LEN_SERVICE_LOCATION_LINE_1,
            self::LEN_SERVICE_LOCATION_LINE_2,
            self::LEN_SERVICE_LOCATION_CITY,
            self::LEN_SERVICE_LOCATION_STATE,
            self::LEN_SERVICE_LOCATION_ZIP_CODE,
            self::LEN_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER,
            self::LEN_BILLING_ADDRESS_STREET_LINE_1,
            self::LEN_BILLING_ADDRESS_STREET_LINE_2,
            self::LEN_BILLING_CITY,
            self::LEN_BILLING_STATE,
            self::LEN_BILLING_ADDRESS_ZIP_CODE,
            self::LEN_BILLING_ADDRESS_PHONE_NUMBER,
            self::LEN_PRIMARY_PROVIDER_SPECIALTY,
            self::LEN_PROVIDER_ORIGINAL_EFFECTIVE_DATE,
            self::LEN_PROVIDER_TERMINATION_DATE,
            self::LEN_SOCIAL_SECURITY_NUMBER,
            self::LEN_STATE_SURCHARGE,
            self::LEN_SURCHARGE_EFFECTIVE_DATE,
            self::LEN_SURCHARGE_TERMINATION_DATE,
            self::LEN_NETWORK_ID,
            self::LEN_NETWORK_TYPE,
            self::LEN_NETWORK_NAME,
            self::LEN_PRODUCT_CODE,
            self::LEN_ALTERNATE_PRODUCT,
            self::LEN_ADD_CHANGE_INDICATOR,
            self::LEN_INACTIVATION_REASON_CODE,
            self::LEN_REPLACED_BY_PIN,
            self::LEN_REJECTION_CODE,
            self::LEN_NPI,
            self::LEN_FILLER,
            self::LEN_TAXONOMYCODE,
            self::LEN_NPI_2,
            self::LEN_LICENSE_NUMBER,
            self::LEN_LICENSE_STATE,
            self::LEN_ECP_FEDERALLY_QUALIFIED_HEALTH_CARE,
            self::LEN_ECP_RYAN_WHITE,
            self::LEN_ECP_INDIAN_PROVIDER,
            self::LEN_ECP_FAMILY_PLANNING,
            self::LEN_ECP_HOSPITAL,
            self::LEN_ECP_OTHER,
            self::LEN_FOREIGN_LANGUAGE1,
            self::LEN_FOREIGN_LANGUAGE2,
            self::LEN_FOREIGN_LANGUAGE3,
            self::LEN_FOREIGN_LANGUAGE4,
            self::LEN_FOREIGN_LANGUAGE5,
            self::LEN_SERVICE_LOCATION_DIRECTORY_INDICATOR,
            self::LEN_SERVICE_LOCATION_HANDICAP_INDICATOR,
            self::LEN_SERVICE_LOCATION_OFFICE_HOURS_MONDAY,
            self::LEN_SERVICE_LOCATION_OFFICE_HOURS_TUESDAY,
            self::LEN_SERVICE_LOCATION_OFFICE_HOURS_WEDNESDAY,
            self::LEN_SERVICE_LOCATION_OFFICE_HOURS_THURSDAY,
            self::LEN_SERVICE_LOCATION_OFFICE_HOURS_FRIDAY,
            self::LEN_SERVICE_LOCATION_OFFICE_HOURS_SATURDAY,
            self::LEN_SERVICE_LOCATION_OFFICE_HOURS_SUNDAY,
            self::LEN_ACCEPTING_NEW_PATIENTS_IND,
            self::LEN_NETWORK_TERMINATION_REASON_CODE,
            self::LEN_AFTERHOURSCARE_IND,
            self::LEN_IOQ_BARIATRIC,
            self::LEN_IOQ_CARDIAC_RHYTHM,
            self::LEN_IOQ_CARDIAC_SURGERY,
            self::LEN_IOQ_CARDIAC_MEDICAL_INTERVENTION,
            self::LEN_IOQ_ORTHOPEDIC_SPINE,
            self::LEN_IOQ_ORTHOPEDIC_TOTAL_JOINT_REPLACEMENT,
            self::LEN_IOE_TRANSPLANT_FACILITY,
            self::LEN_IOE_HOSPITAL,
            self::LEN_IOE_INFERTILITY_PROGRAM,
            self::LEN_IOE_IVF_AFFILIATED_PHYSICIAN,
            self::LEN_PRIMARY_PROVIDER_SPECIALTY_BOARD_CERTIFICATION_YEAR,
            self::LEN_MEDICAL_GROUP_NAME,
            self::LEN_HOSPITAL_AFFILIATION_1,
            self::LEN_HOSPITAL_AFFILIATION_2,
            self::LEN_HOSPITAL_AFFILIATION_3,
            self::LEN_HOSPITAL_AFFILIATION_4,
            self::LEN_HOSPITAL_AFFILIATION_5,
            self::LEN_PROVIDER_GENDER,
            self::LEN_EDUCATION_SCHOOL_NAME,
            self::LEN_EDUCATION_SCHOOL_GRADUATION_YEAR,
            self::LEN_AEXCEL_HIGH_PERFORMANCE_PHYSICIAN_NETWORK,
            self::LEN_SPECIALTY2,
            self::LEN_SPECIALTY3,
        ];
    }

    public function skipRow(array $row): bool
    {
        if ($row[self::COL_SERVICE_LOCATION_STATE] !== $this->texas->code) {
            return true;
        }

        switch ($row[self::COL_ADD_CHANGE_INDICATOR]) {
            case self::ADD_CHANGE_INDICATOR_ADD:
            case self::ADD_CHANGE_INDICATOR_REINSTATE:
            case self::ADD_CHANGE_INDICATOR_CHANGED:
                return false;

            case self::ADD_CHANGE_INDICATOR_TERMINATED:
            case self::ADD_CHANGE_INDICATOR_INACTIVATED:
            default:
                return true;
        }
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
            'label'            => self::COL_TIN_OWNER_NAME,
            'address_line_1'   => self::COL_SERVICE_LOCATION_LINE_1,
            'address_city'     => self::COL_SERVICE_LOCATION_CITY,
            'address_state_id' => function (array $item) {
                return State::findByCodeOrFail($item[self::COL_SERVICE_LOCATION_STATE])->id;
            },
            'address_zip'      => self::COL_SERVICE_LOCATION_ZIP_CODE,
            'phone'            => self::COL_SERVICE_LOCATION_PRIMARY_PHONE_NUMBER,
        ];
    }

    protected function getSpecialityKeys(): array
    {
        return [
            [
                self::COL_PRIMARY_PROVIDER_SPECIALTY,
                fn(string $key) => Mapper\Aenta\SpecialityMap::lookup($key),
            ],
            [
                self::COL_SPECIALTY2,
                fn(string $key) => Mapper\Aenta\SpecialityMap::lookup($key),
            ],
            [
                self::COL_SPECIALTY3,
                fn(string $key) => Mapper\Aenta\SpecialityMap::lookup($key),
            ],
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
                    ? trim($item[self::COL_PROVIDER_LAST_NAME])
                    : trim(sprintf(
                        '%s %s',
                        $item[self::COL_PROVIDER_FIRST_NAME],
                        $item[self::COL_PROVIDER_LAST_NAME],
                    ));
            },
            'type'                      => self::COL_PROVIDER_TYPE,
            'npi'                       => (int) $this->getProviderNpiKey(),
            'degree'                    => self::COL_PROVIDER_DEGREE,
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
}
