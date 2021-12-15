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
                'config_key'      => 'datasource.source.aenta',
            ],
            [
                'label'           => 'HCH',
                'search_label'    => 'Medical providers',
                'search_sublabel' => 'Secure choice <em>Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Secure Choice Select',
                'config_key'      => 'datasource.source.hch',
            ],
            [
                'label'           => 'VSP',
                'search_label'    => 'Vision providers',
                'search_sublabel' => 'Secure choice <em>Broad & Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Vision Providers',
                'config_key'      => 'datasource.source.vsp',
            ],
            [
                'label'           => 'CIGNA',
                'search_label'    => 'Pharmacy directory',
                'search_sublabel' => 'Secure choice <em>Broad & Select</em>',
                'network_label'   => 'Allstate Benefits Secure Choice',
                'browse_label'    => 'Pharmacy Providers',
                'config_key'      => 'datasource.source.cigna',
            ],
        ];

        foreach ($networks as $network) {
            Network::create([
                'label'           => $network['label'],
                'search_label'    => $network['search_label'],
                'search_sublabel' => $network['search_sublabel'],
                'network_label'   => $network['network_label'],
                'browse_label'    => $network['browse_label'],
                'config_key'      => $network['config_key'],
            ]);
        }
    }
}
