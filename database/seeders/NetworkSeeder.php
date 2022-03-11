<?php

namespace Database\Seeders;

use App\Models\Network;
use Illuminate\Database\Seeder;

class NetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $networks = [
            //  AENTA
            [
                'label'           => 'AENTA',
                'search_label'    => 'Medical providers',
                'search_sublabel' => 'Secure choice <em>Broad</em>',
                'network_label'   => 'Allstate Benefits Secure Choice - Broad',
                'browse_label'    => 'Secure Choice Broad',
                'legal_home'      => implode(PHP_EOL, [
                    '<p>Aetna PPO Network through the Aetna Signature Administrators® program provides medical and dental network access for Allstate Benefits Secure Choice – Broad members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_search'    => implode(PHP_EOL, [
                    '<p>Aetna PPO Network through the Aetna Signature Administrators® program provides medical and dental network access for Allstate Benefits Secure Choice – Broad members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_browse'    => implode(PHP_EOL, [
                    '<p>Aetna PPO Network through the Aetna Signature Administrators® program provides medical and dental network access for Allstate Benefits Secure Choice – Broad members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_provider'  => implode(PHP_EOL, [
                    '<p>Aetna PPO Network through the Aetna Signature Administrators® program provides medical and dental network access for Allstate Benefits Secure Choice – Broad members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_facility'  => implode(PHP_EOL, [
                    '<p>Aetna PPO Network through the Aetna Signature Administrators® program provides medical and dental network access for Allstate Benefits Secure Choice – Broad members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'config_key'      => 'datasource.source.aenta',
            ],

            //  HCH
            [
                'label'           => 'HCH',
                'search_label'    => 'Medical providers',
                'search_sublabel' => 'Secure choice <em>Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice - Select',
                'browse_label'    => 'Secure Choice Select',
                'legal_home'      => implode(PHP_EOL, [
                    '<p>The Allstate Benefits Secure Choice - Select providers listed in this online directory are accessible to health plan members through a contract arrangement with Healthcare Highways. All Healthcare Highways providers should recognize the combined logos on your member ID card.</p>',
                    '<p>The Healthcare Highways Sync Texas Fully Insured provides medical network access for Allstate Benefits Secure Choice – Select members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_search'    => implode(PHP_EOL, [
                    '<p>The Allstate Benefits Secure Choice - Select providers listed in this online directory are accessible to health plan members through a contract arrangement with Healthcare Highways. All Healthcare Highways providers should recognize the combined logos on your member ID card.</p>',
                    '<p>The Healthcare Highways Sync Texas Fully Insured provides medical network access for Allstate Benefits Secure Choice – Select members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_browse'    => implode(PHP_EOL, [
                    '<p>The Allstate Benefits Secure Choice - Select providers listed in this online directory are accessible to health plan members through a contract arrangement with Healthcare Highways. All Healthcare Highways providers should recognize the combined logos on your member ID card.</p>',
                    '<p>The Healthcare Highways Sync Texas Fully Insured provides medical network access for Allstate Benefits Secure Choice – Select members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_provider'  => implode(PHP_EOL, [
                    '<p>The Allstate Benefits Secure Choice - Select providers listed in this online directory are accessible to health plan members through a contract arrangement with Healthcare Highways. All Healthcare Highways providers should recognize the combined logos on your member ID card.</p>',
                    '<p>The Healthcare Highways Sync Texas Fully Insured provides medical network access for Allstate Benefits Secure Choice – Select members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_facility'  => implode(PHP_EOL, [
                    '<p>The Allstate Benefits Secure Choice - Select providers listed in this online directory are accessible to health plan members through a contract arrangement with Healthcare Highways. All Healthcare Highways providers should recognize the combined logos on your member ID card.</p>',
                    '<p>The Healthcare Highways Sync Texas Fully Insured provides medical network access for Allstate Benefits Secure Choice – Select members.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'config_key'      => 'datasource.source.hch',
            ],

            //  VSP
            [
                'label'           => 'VSP',
                'search_label'    => 'Vision providers',
                'search_sublabel' => 'Secure choice <em>Broad & Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Vision Providers',
                'legal_home'      => implode(PHP_EOL, [
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_search'    => implode(PHP_EOL, [
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_browse'    => implode(PHP_EOL, [
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_provider'  => implode(PHP_EOL, [
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_facility'  => implode(PHP_EOL, [
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'config_key'      => 'datasource.source.vsp',
            ],

            //  CIGNA
            [
                'label'           => 'CIGNA',
                'search_label'    => 'Pharmacy directory',
                'search_sublabel' => 'Secure choice <em>Broad & Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Pharmacy Providers',
                'legal_home'      => implode(PHP_EOL, [
                    '<p>Cigna is an independent company and not affiliated with Allstate Benefits. Access to the Cigna Pharmacy 90 Now Network is available through Cigna\'s contractual relationship with Allstate Benefits.</p>',
                    '<p>All Cigna products are provided exclusively by or through operating subsidiaries of Cigna Corporation, including Cigna Health and Life Insurance Company. The Cigna name, logo, and other Cigna marks are owned by Cigna Intellectual Property, Inc.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_search'    => implode(PHP_EOL, [
                    '<p>Cigna is an independent company and not affiliated with Allstate Benefits. Access to the Cigna Pharmacy 90 Now Network is available through Cigna\'s contractual relationship with Allstate Benefits.</p>',
                    '<p>All Cigna products are provided exclusively by or through operating subsidiaries of Cigna Corporation, including Cigna Health and Life Insurance Company. The Cigna name, logo, and other Cigna marks are owned by Cigna Intellectual Property, Inc.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_browse'    => implode(PHP_EOL, [
                    '<p>Cigna is an independent company and not affiliated with Allstate Benefits. Access to the Cigna Pharmacy 90 Now Network is available through Cigna\'s contractual relationship with Allstate Benefits.</p>',
                    '<p>All Cigna products are provided exclusively by or through operating subsidiaries of Cigna Corporation, including Cigna Health and Life Insurance Company. The Cigna name, logo, and other Cigna marks are owned by Cigna Intellectual Property, Inc.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_provider'  => implode(PHP_EOL, [
                    '<p>Cigna is an independent company and not affiliated with Allstate Benefits. Access to the Cigna Pharmacy 90 Now Network is available through Cigna\'s contractual relationship with Allstate Benefits.</p>',
                    '<p>All Cigna products are provided exclusively by or through operating subsidiaries of Cigna Corporation, including Cigna Health and Life Insurance Company. The Cigna name, logo, and other Cigna marks are owned by Cigna Intellectual Property, Inc.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'legal_facility'  => implode(PHP_EOL, [
                    '<p>Cigna is an independent company and not affiliated with Allstate Benefits. Access to the Cigna Pharmacy 90 Now Network is available through Cigna\'s contractual relationship with Allstate Benefits.</p>',
                    '<p>All Cigna products are provided exclusively by or through operating subsidiaries of Cigna Corporation, including Cigna Health and Life Insurance Company. The Cigna name, logo, and other Cigna marks are owned by Cigna Intellectual Property, Inc.</p>',
                    '<p>Group health insurance plans offered by Allstate Benefits are underwritten by Integon National Insurance Company.</p>',
                    '<p>This online directory is updated weekly, but it is for reference only. We make every effort to ensure current, accurate data, but changes occur daily and may not be reflected immediately.  If you feel you have identified a discrepancy, please contact us at <a href="mailto:ABGHProviderDirectory@NGIC.COM">ABGHProviderDirectory@NGIC.COM</a>.</p>',
                ]),
                'config_key'      => 'datasource.source.cigna',
            ],
        ];

        foreach ($networks as $network) {
            Network::create($network);
        }
    }
}
