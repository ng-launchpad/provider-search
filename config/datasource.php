<?php

use App\Services\DataSource\Connection;
use App\Services\DataSource\Mapper;
use App\Services\DataSource\Parser;

return [
    'source' => [
        'aenta' => [
            'connection' => [
                'class'  => Connection\Sftp::class,
                'config' => [
                    'host'     => env('DATASOURCE_SFTP_HOST'),
                    'port'     => env('DATASOURCE_SFTP_PORT'),
                    'username' => env('DATASOURCE_SFTP_USERNAME'),
                    'password' => env('DATASOURCE_SFTP_PASSWORD'),
                ],
            ],
            'mapper'     => Mapper\Aenta::class,
            'parser'     => Parser\TextColumns::class,
        ],
        'hch'   => [
            'connection' => [
                'class'  => Connection\Ssh::class,
                'config' => [
                    'host'        => env('DATASOURCE_SSH_HOST'),
                    'port'        => env('DATASOURCE_SSH_PORT'),
                    'private_key' => env('DATASOURCE_SSH_PRIVATE_KEY'),
                ],
            ],
            'mapper'     => Mapper\Hch::class,
            'parser'     => Parser\Xls::class,
        ],
        'vsp'   => [
            'connection' => [
                'class'  => Connection\Ssh::class,
                'config' => [
                    'host'        => env('DATASOURCE_SSH_HOST'),
                    'port'        => env('DATASOURCE_SSH_PORT'),
                    'private_key' => env('DATASOURCE_SSH_PRIVATE_KEY'),
                ],
            ],
            'mapper'     => Mapper\Vsp::class,
            'parser'     => Parser\Csv::class,
        ],
        'cigna' => [
            'connection' => [
                'class'  => Connection\Ssh::class,
                'config' => [
                    'host'        => env('DATASOURCE_SSH_HOST'),
                    'port'        => env('DATASOURCE_SSH_PORT'),
                    'private_key' => env('DATASOURCE_SSH_PRIVATE_KEY'),
                ],
            ],
            'mapper'     => Mapper\Cigna::class,
            'parser'     => Parser\Xls::class,
        ],
    ],
];
