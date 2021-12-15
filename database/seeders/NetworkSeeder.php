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
                'label'         => 'AENTA',
                'label_search'  => 'Medical & dental providers',
                'label_network' => 'Allstate Benefits Secure Choice',
                'label_browse'  => 'Secure Choice Broad',
                'config_key'    => 'datasource.source.aenta',
            ],
            [
                'label'         => 'HCH',
                'label_search'  => 'Medical providers',
                'label_network' => 'Allstate Benefits Secure Choice',
                'label_browse'  => 'Secure Choice Select',
                'config_key'    => 'datasource.source.hch',
            ],
            [
                'label'         => 'VSP',
                'label_search'  => 'Vision providers',
                'label_network' => 'Allstate Benefits Secure Choice',
                'label_browse'  => 'Vision Providers',
                'config_key'    => 'datasource.source.vsp',
            ],
            [
                'label'         => 'CIGNA',
                'label_search'  => 'Pharmacy directory',
                'label_network' => 'Allstate Benefits Secure Choice',
                'label_browse'  => 'Pharmacy Providers',
                'config_key'    => 'datasource.source.cigna',
            ],
        ];

        foreach ($networks as $network) {
            Network::create([
                'label'         => $network['label'],
                'label_search'  => $network['label_search'],
                'label_network' => $network['label_network'],
                'label_browse'  => $network['label_browse'],
                'config_key'    => $network['config_key'],
            ]);
        }
    }
}
