<?php

use App\Services\DataSource\Connection;
use App\Services\DataSource\Mapper;
use App\Services\DataSource\Parser;

return [
    'source' => [
        'aenta' => [
            'path'       => '/path/to/file.txt',
            'connection' => [
                'class'  => Connection\Sftp::class,
                'config' => [
                    'host'     => env('DATASOURCE_SFTP_HOST'),
                    'username' => env('DATASOURCE_SFTP_USERNAME'),
                    'password' => env('DATASOURCE_SFTP_PASSWORD'),
                    'port'     => env('DATASOURCE_SFTP_PORT'),
                ],
            ],
            'mapper'     => [
                'class'  => Mapper\Aenta::class,
                'config' => [],
            ],
            'parser'     => [
                'class'  => Parser\TextColumns::class,
                'config' => [
                    'columnMap' => Mapper\Aenta::getColumnLengths(),
                ],
            ],
        ],
        'hch'   => [
            'path'       => '/path/to/file.xls',
            'connection' => [
                'class'  => Connection\Ssh::class,
                'config' => [
                    'host'        => env('DATASOURCE_SSH_HOST'),
                    'port'        => env('DATASOURCE_SSH_PORT'),
                    'private_key' => env('DATASOURCE_SSH_PRIVATE_KEY'),
                ],
            ],
            'mapper'     => [
                'class'  => Mapper\Hch::class,
                'config' => [],
            ],
            'parser'     => [
                'class'  => Parser\Xls::class,
                'config' => [],
            ],
        ],
        'vsp'   => [
            'path'       => '/path/to/file.csv',
            'connection' => [
                'class'  => Connection\Ssh::class,
                'config' => [
                    'host'        => env('DATASOURCE_SSH_HOST'),
                    'port'        => env('DATASOURCE_SSH_PORT'),
                    'private_key' => env('DATASOURCE_SSH_PRIVATE_KEY'),
                ],
            ],
            'mapper'     => [
                'class'  => Mapper\Vsp::class,
                'config' => [],
            ],
            'parser'     => [
                'class'  => Parser\Csv::class,
                'config' => [],
            ],
        ],
        'cigna' => [
            'path'       => '/path/to/file.xls',
            'connection' => [
                'class'  => Connection\Ssh::class,
                'config' => [
                    'username'    => (string) env('DATASOURCE_SSH_USER'),
                    'host'        => (string) env('DATASOURCE_SSH_HOST'),
                    'port'        => (string) env('DATASOURCE_SSH_PORT'),
                    'private_key' => (string) env('DATASOURCE_SSH_PRIVATE_KEY'),
                ],
            ],
            'mapper'     => [
                'class'  => Mapper\Cigna::class,
                'config' => [],
            ],
            'parser'     => [
                'class'  => Parser\Xls::class,
                'config' => [],
            ],
        ],
    ],
];
