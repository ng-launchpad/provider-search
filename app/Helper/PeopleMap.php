<?php

namespace App\Helper;

final class PeopleMap
{

    const GROUP_ANESTHESIOLOGISTS               = 'Anesthesiologists';
    const GROUP_RADIOLOGISTS                    = 'Radiologists';
    const GROUP_PATHOLOGISTS                    = 'Pathologists';
    const GROUP_EMERGENCY_DEPARTMENT_PHYSICIANS = 'Emergency Department Physicians';
    const GROUP_NEONATOLOGISTS                  = 'Neonatologists';
    const GROUP_ASSISTANT_SURGEONS              = 'Assistant Surgeons';

    const MAP = [
        'Pediatric Anesthesiology'                    => self::GROUP_ANESTHESIOLOGISTS,
        'Anesthesiology'                              => self::GROUP_ANESTHESIOLOGISTS,
        'Critical Care Medicine/Anesthesiology'       => self::GROUP_ANESTHESIOLOGISTS,
        'Anesthesiology: Hospice and Palliative Care' => self::GROUP_ANESTHESIOLOGISTS,
        'Sleep Medicine - Anesthesiology'             => self::GROUP_ANESTHESIOLOGISTS,
        'Dental Anesthesia'                           => self::GROUP_ANESTHESIOLOGISTS,
        'Anesthesiology Assistant'                    => self::GROUP_ANESTHESIOLOGISTS,
        'Registered Nurse Anesthetist'                => self::GROUP_ANESTHESIOLOGISTS,
        'Radiology: Nuclear/Nuclear Medicine'         => self::GROUP_RADIOLOGISTS,
        'Radiology'                                   => self::GROUP_RADIOLOGISTS,
        'Radiology: Diagnostic'                       => self::GROUP_RADIOLOGISTS,
        'Radiological Physics'                        => self::GROUP_RADIOLOGISTS,
        'Radiology: Nuclear'                          => self::GROUP_RADIOLOGISTS,
        'Pediatric Radiology'                         => self::GROUP_RADIOLOGISTS,
        'Therapeutic Radiology'                       => self::GROUP_RADIOLOGISTS,
        'Neuroradiology'                              => self::GROUP_RADIOLOGISTS,
        'Vascular & Interventional Radiology'         => self::GROUP_RADIOLOGISTS,
        'Radiology: Hospice and Palliative Care'      => self::GROUP_RADIOLOGISTS,
        'Pediatric Interventional Neuroradiology'     => self::GROUP_RADIOLOGISTS,
        'Pediatric Interventional Radiology'          => self::GROUP_RADIOLOGISTS,
        'Pathology: Radioisotopic/Nuclear Medicine'   => self::GROUP_PATHOLOGISTS,
        'Pathology: Anatomic & Clinical'              => self::GROUP_PATHOLOGISTS,
        'Pathology: Anatomic'                         => self::GROUP_PATHOLOGISTS,
        'Pathology: Clinical'                         => self::GROUP_PATHOLOGISTS,
        'Pathology: Chemical'                         => self::GROUP_PATHOLOGISTS,
        'Pathology: Forensic'                         => self::GROUP_PATHOLOGISTS,
        'Pathology: Radioisotopic'                    => self::GROUP_PATHOLOGISTS,
        'Pediatric Pathology'                         => self::GROUP_PATHOLOGISTS,
        'Pathology'                                   => self::GROUP_PATHOLOGISTS,
        'Pathology & Laboratory Medicine'             => self::GROUP_PATHOLOGISTS,
        'Pathology: Medical Genetics'                 => self::GROUP_PATHOLOGISTS,
        'Pathology: Oral and Maxillofacial'           => self::GROUP_PATHOLOGISTS,
        'Emergency Medicine'                          => self::GROUP_EMERGENCY_DEPARTMENT_PHYSICIANS,
        'Neonatal-Perinatal Medicine'                 => self::GROUP_NEONATOLOGISTS,
        'Neonatology'                                 => self::GROUP_NEONATOLOGISTS,
        'Non Physician Surgical Assistant'            => self::GROUP_ASSISTANT_SURGEONS,
    ];

    public static function getGroups(): array
    {
        $groups = array_unique(
            array_values(self::MAP)
        );

        sort($groups);

        return $groups;
    }

    public static function getSpecialities(): array
    {
        return array_unique(
            array_keys(self::MAP)
        );
    }

    public static function specialityMapsTo(string $speciality): ?string
    {
        return self::MAP[$speciality] ?? null;
    }
}
