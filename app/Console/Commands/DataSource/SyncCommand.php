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
        $service = DataSourceService::factory();

        $this
            ->performSync(
                $service,
                'Aenta',
                'filename.xlsx',
                new Mapper\Aenta(),
                new Parser\Xls()
            )
            ->performSync(
                $service,
                'VSP',
                'filename.csv',
                new Mapper\Vsp(),
                new Parser\Csv()
            );

        return Command::SUCCESS;
    }

    private function getSftpConnection(): Connection\Sftp
    {
        return Connection\Sftp::factory(
            env('DATASOURCE_SFTP_HOST'),
            env('DATASOURCE_SFTP_USERNAME'),
            env('DATASOURCE_SFTP_PASSWORD')
        );
    }

    private function performSync(
        DataSourceService $service,
        string $label,
        string $filename,
        \App\Services\DataSource\Interfaces\Mapper $mapper,
        \App\Services\DataSource\Interfaces\Parser $parser
    ): self {

        try {

            $this->getOutput()->write(sprintf(
                'Syncing <comment>%s</comment> data... ',
                $label
            ));

            $service
                ->sync(
                    $filename,
                    $this->getSftpConnection(),
                    $mapper,
                    $parser
                );

            $this->getOutput()->writeln('<info>done!</info>');

        } catch (\Throwable $e) {
            $this->getOutput()->writeln(sprintf(
                '<error>ERROR: %s</error>',
                $e->getMessage()
            ));
        }
        return $this;
    }
}
