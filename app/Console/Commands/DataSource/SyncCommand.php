<?php

namespace App\Console\Commands\DataSource;

use App\Services\DataSource\Connection;
use App\Services\DataSource\Mapper;
use App\Services\DataSource\Parser;
use App\Services\DataSourceService;
use Illuminate\Console\Command;

class SyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datasource:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs upstream data sources into the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DataSourceService::factory()
            ->sync(
                'filename.xlsx',
                new Connection\Sftp(),
                new Mapper\SourceOne(),
                new Parser\Xls()
            )
            ->sync(
                'filename.csv',
                new Connection\Sftp(),
                new Mapper\SourceTwo(),
                new Parser\Csv()
            );

        return Command::SUCCESS;
    }
}
