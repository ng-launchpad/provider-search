<?php

use App\Services\DataSource\Connection;
use App\Services\DataSource\Mapper;
use App\Services\DataSource\Parser;

return [
    'contact' => env('DATASOURCE_CONTACT'),
    'source'  => [
        'aenta' => [
            'path'       => '/Provider',
            'connection' => [
                'class'  => Connection\Sftp::class,
                'config' => [
                    'host'     => env('DATASOURCE_SFTP_COFINITY_HOST'),
                    'username' => env('DATASOURCE_SFTP_COFINITY_USERNAME'),
                    'password' => env('DATASOURCE_SFTP_COFINITY_PASSWORD'),
                    'port'     => env('DATASOURCE_SFTP_COFINITY_PORT'),
                ],
            ],
            'mapper'     => [
                'class'  => Mapper\Aenta::class,
                'config' => [],
            ],
            'parser'     => [
                'class'  => Parser\TextColumns::class,
                'config' => [
                    'offset'    => 0,
                    'columnMap' => Mapper\Aenta::getColumnLengths(),
                ],
            ],
        ],
        'hch'   => [
            'path'       => '/HCH_Providers',
            'connection' => [
                'class'  => Connection\Sftp::class,
                'config' => [
                    'host'     => env('DATASOURCE_SFTP_EXAVAULT_HOST'),
                    'username' => env('DATASOURCE_SFTP_EXAVAULT_USERNAME'),
                    'password' => env('DATASOURCE_SFTP_EXAVAULT_PASSWORD'),
                    'port'     => env('DATASOURCE_SFTP_EXAVAULT_PORT'),
                ],
            ],
            'mapper'     => [
                'class'  => Mapper\Hch::class,
                'config' => [],
            ],
            'parser'     => [
                'class'  => Parser\Csv::class,
                'config' => [
                    'offset' => 1,
                ],
            ],
        ],
        'vsp'   => [
            'path'       => '/vsp_providers',
            'connection' => [
                'class'  => Connection\Sftp::class,
                'config' => [
                    'host'     => env('DATASOURCE_SFTP_EXAVAULT_HOST'),
                    'username' => env('DATASOURCE_SFTP_EXAVAULT_USERNAME'),
                    'password' => env('DATASOURCE_SFTP_EXAVAULT_PASSWORD'),
                    'port'     => env('DATASOURCE_SFTP_EXAVAULT_PORT'),
                ],
            ],
            'mapper'     => [
                'class'  => Mapper\Vsp::class,
                'config' => [],
            ],
            'parser'     => [
                'class'  => Parser\Xls::class,
                'config' => [
                    'offset' => 4,
                ],
            ],
        ],
        'cigna' => [
            'path'       => '/ExpressScripts_provider',
            'connection' => [
                'class'  => Connection\Sftp::class,
                'config' => [
                    'host'     => env('DATASOURCE_SFTP_EXAVAULT_HOST'),
                    'username' => env('DATASOURCE_SFTP_EXAVAULT_USERNAME'),
                    'password' => env('DATASOURCE_SFTP_EXAVAULT_PASSWORD'),
                    'port'     => env('DATASOURCE_SFTP_EXAVAULT_PORT'),
                ],
            ],
            'mapper'     => [
                'class'  => Mapper\Cigna::class,
                'config' => [],
            ],
            'parser'     => [
                'class'  => Parser\Xls::class,
                'config' => [
                    'offset' => 1,
                ],
            ],
        ],
    ],
];
