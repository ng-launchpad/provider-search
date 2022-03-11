<?php

namespace App\Services\DataSource\Mapper\Aenta;

final class TypeMap
{
    const MAP = [
        'ABA' => 'Applied Behavioral Analysis',
        'AC'  => 'Ambulatory Surgicenter',
        'ACM' => 'Accommodations',
        'ADC' => 'Adult Day Care',
        'AFH' => 'Adult Foster Home',
        'ALC' => 'Assisted Living Center',
        'AP'  => 'Acupuncturist',
        'AR'  => 'Acute Rehabilitation Facility',
        'ART' => 'Art Therapist',
        'AT'  => 'Athletic Trainer',
        'AU'  => 'Audiologist',
        'BAA' => 'Behavioral Analysis Assistant',
        'BB'  => 'Freestanding Blood Bank',
        'BC'  => 'Freestanding Birthing Center',
        'BH1' => 'Delegate PM Behavioral Health Group',
        'BHG' => 'Behavioral Health Provider Group',
        'BHR' => 'Behavioral Health Rehabilitation Services',
        'BT'  => 'Biofeedback Technician',
        'CAC' => 'Certified Addictions Counselor (NY Only)',
        'CF'  => 'Convalescent Care Facility',
        'CH'  => 'Children\'s Hospital',
        'CMC' => 'Community Mental Health Center',
        'CMT' => 'Case Management',
        'CP'  => 'Clinical Psychologist',
        'CR'  => 'Crisis Stabilization Program',
        'CS'  => 'Christian Science Practitioner',
        'DA'  => 'Diagnostic Testing Center',
        'DAC' => 'Drug and Alcohol Counselor',
        'DC'  => 'Chiropractor',
        'DE'  => 'Independent Durable Med Equipment',
        'DHB' => 'Day Habilitation',
        'DHY' => 'Dental Hygienist',
        'DI'  => 'Dialysis Center',
        'DLA' => 'DOULA',
        'DN'  => 'Dentist',
        'DNT' => 'Denturist',
        'DP'  => 'Podiatrist',
        'DPG' => 'Multi Dental Provider Group',
        'DT'  => 'Registered Dietician',
        'DTC' => 'Diabetic Treatment Center',
        'ECS' => 'BH Expanded Covered Services',
        'FEC' => 'Freestanding Emergency Center',
        'FQC' => 'Federally Qualified Health Center',
        'GNC' => 'Genetic Counselor',
        'HA'  => 'Home Health Care Agency',
        'HBM' => 'Hyperbaric Medicine - Facility',
        'HCO' => 'HAI Clinical Office',
        'HDM' => 'Home Delivered Meals',
        'HI'  => 'Home Infusion',
        'HMD' => 'Home Modification',
        'HO'  => 'Acute Short Term Hospital',
        'HOM' => 'Homemaking',
        'HS'  => 'Freestanding Hospice',
        'IC'  => 'Infusion Center',
        'ICF' => 'Intermediate Care Facility',
        'IDN' => 'International Dentist',
        'IO'  => 'Intensive Outpatient Program',
        'LB'  => 'Independent Lab',
        'LC'  => 'Lactation Consultants',
        'LHO' => 'Long Term Acute Care Hospital',
        'LPC' => 'Licensed Professional Counselor',
        'LPN' => 'Nurse: Licensed Practical',
        'LT'  => 'Lithotripsy',
        'MAT' => 'Medication Assisted Treatment',
        'MPG' => 'Multi Provider Group',
        'MRI' => 'MRI Center',
        'MSS' => 'Medical Social Services',
        'MST' => 'Massage Therapist',
        'MT'  => 'Marriage/Family Therapist',
        'MTF' => 'Military Treatment Facility',
        'MW'  => 'Midwife',
        'NC'  => 'Nursing Care Agency',
        'ND'  => 'Naturopath',
        'NP'  => 'Nurse Practitioner',
        'NPB' => 'Nurse Practitioner-BH',
        'NPS' => 'Neuropsychologist',
        'NSA' => 'Non-Physician Surgical Assistant',
        'NUT' => 'Nutritionist',
        'OBS' => 'Office Based Surgery',
        'OF'  => 'Orthotics Facility',
        'OMC' => 'Occupational Medicine Clinic',
        'OMP' => 'Other Medical Provider',
        'ON'  => 'Optical Provider',
        'OP'  => 'Optometrist',
        'OR'  => 'Oral Surgeon',
        'OT'  => 'Occupational Therapist',
        'OTC' => 'Oncology Treatment Center',
        'OTV' => 'Other Trade Vendor',
        'PAB' => 'Physician Assistant-BH',
        'PAS' => 'Physician Assistant',
        'PC'  => 'Pastoral Counselor',
        'PD'  => 'Partial Hospital/Day Programs',
        'PE'  => 'Psychological Examiner',
        'PER' => 'Personal Emergency Response System',
        'PH'  => 'Physician',
        'PM'  => 'Pharmacist',
        'PMC' => 'Pain Management Center',
        'PN'  => 'Psychiatric Nurse',
        'PRC' => 'Personal Care',
        'PSH' => 'Psychiatric Hospital: Acute and Long Term',
        'PSS' => 'Peer Support Specialist',
        'PT'  => 'Physical Therapist',
        'PXS' => 'Portable Xray Supplier',
        'QFP' => 'Qualified Family Planning Provider',
        'RC'  => 'Radiology Center',
        'RFA' => 'Registered Nurse - First Assistant',
        'RFX' => 'Radiology Facility with Portable Xray',
        'RHC' => 'Rural Health Clinic',
        'RN'  => 'Registered Nurse',
        'RNA' => 'Registered Nurse Anesthetist',
        'RSC' => 'Respite Care',
        'RT'  => 'Respiratory Therapist',
        'RTF' => 'Residential Treatment Facility',
        'RX'  => 'Pharmacy',
        'SA'  => 'Substance Abuse Facility',
        'SBH' => 'School Based Health Center',
        'SD'  => 'Sleep Diagnostic Center',
        'SDC' => 'Social Day Care',
        'SES' => 'Social & Environmental Supports',
        'SH'  => 'Speech Pathologist',
        'SK'  => 'Skilled Nursing Facility',
        'ST'  => 'Speech Therapist',
        'SW'  => 'Clinical Social Worker',
        'SWP' => 'Physician Supervised Weight Loss Program',
        'TA'  => 'Transportation (Air)',
        'TL'  => 'Transportation (Land)',
        'TM'  => 'Transportation Medicaid',
        'TW'  => 'Transportation (Water)',
        'UC'  => 'Urgent Care Center',
        'VIP' => 'Voluntary Interruption of Pregnancy Center',
    ];

    public static function lookup(string $key): ?string
    {
        return self::MAP[$key] ?? null;
    }
}