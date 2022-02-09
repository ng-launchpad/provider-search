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
            [
                'label'           => 'AENTA',
                'search_label'    => 'Medical & dental providers',
                'search_sublabel' => 'Secure choice <em>Broad</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Secure Choice Broad',
                'legal_home'      => '<p>@todo - AENTA specific legal text for the home page</p>',
                'legal_search'    => '<p>@todo - AENTA specific legal text for the search results page</p>',
                'legal_browse'    => '<p>@todo - AENTA specific legal text for the browse page</p>',
                'legal_provider'  => '<p>@todo - AENTA specific legal text for the provider pages</p>',
                'legal_facility'  => '<p>@todo - AENTA specific legal text for the facility pages</p>',
                'config_key'      => 'datasource.source.aenta',
            ],
            [
                'label'           => 'HCH',
                'search_label'    => 'Medical providers',
                'search_sublabel' => 'Secure choice <em>Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Secure Choice Select',
                'legal_home'      => '<p>@todo - HCH specific legal text for the home page</p>',
                'legal_search'    => '<p>@todo - HCH specific legal text for the search results page</p>',
                'legal_browse'    => '<p>@todo - HCH specific legal text for the browse page</p>',
                'legal_provider'  => '<p>@todo - HCH specific legal text for the provider pages</p>',
                'legal_facility'  => '<p>@todo - HCH specific legal text for the facility pages</p>',
                'config_key'      => 'datasource.source.hch',
            ],
            [
                'label'           => 'VSP',
                'search_label'    => 'Vision providers',
                'search_sublabel' => 'Secure choice <em>Broad & Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Vision Providers',
                'legal_home'      => '<p>@todo - VSP specific legal text for the home page</p>',
                'legal_search'    => '<p>@todo - VSP specific legal text for the search results page</p>',
                'legal_browse'    => '<p>@todo - VSP specific legal text for the browse page</p>',
                'legal_provider'  => '<p>@todo - VSP specific legal text for the provider pages</p>',
                'legal_facility'  => '<p>@todo - VSP specific legal text for the facility pages</p>',
                'config_key'      => 'datasource.source.vsp',
            ],
            [
                'label'           => 'CIGNA',
                'search_label'    => 'Pharmacy directory',
                'search_sublabel' => 'Secure choice <em>Broad & Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Pharmacy Providers',
                'legal_home'      => '<p>@todo - CIGNA specific legal text for the home page</p>',
                'legal_search'    => '<p>@todo - CIGNA specific legal text for the search results page</p>',
                'legal_browse'    => '<p>@todo - CIGNA specific legal text for the browse page</p>',
                'legal_provider'  => '<p>@todo - CIGNA specific legal text for the provider pages</p>',
                'legal_facility'  => '<p>@todo - CIGNA specific legal text for the facility pages</p>',
                'config_key'      => 'datasource.source.cigna',
            ],
        ];

        foreach ($networks as $network) {
            Network::create($network);
        }
    }
}
