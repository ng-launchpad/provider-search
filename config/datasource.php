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
            'mapper'     => Mapper\Aenta::class,
            'parser'     => Parser\TextColumns::class,
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
            'mapper'     => Mapper\Hch::class,
            'parser'     => Parser\Xls::class,
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
            'mapper'     => Mapper\Vsp::class,
            'parser'     => Parser\Csv::class,
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
            'mapper'     => Mapper\Cigna::class,
            'parser'     => Parser\Xls::class,
        ],
    ],
];
