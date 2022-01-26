<?php

namespace App\Console\Commands\DataSource;

use App\Models\Network;
use App\Models\Setting;
use App\Services\DataSource\Connection;
use App\Services\DataSource\Mapper;
use App\Services\DataSource\Parser;
use App\Services\DataSourceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputOption;

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
        $this->addOption('network', 'N', InputOption::VALUE_REQUIRED, 'Specify the network to sync');
        $this->addOption('file', 'F', InputOption::VALUE_REQUIRED, 'Specify a local file to use (overrides the remote connection)');
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Throwable
     */
    public function handle()
    {
        $service = DataSourceService::factory();

        try {

            DB::transaction(function () use ($service) {

                $networks = $this->input->getOption('network')
                    ? [Network::getByLabelOrFail($this->input->getOption('network'))]
                    : Network::all();

                foreach ($networks as $network) {

                    $this->getOutput()->write(sprintf(
                        'Syncing <comment>%s</comment> data... ',
                        $network->label
                    ));

                    $config = $network->getConfig();
                    $path   = $config['path'];

                    $connection = $this->input->getOption('file')
                        ? Connection\Local::factory($this->input->getOption('file'))
                        : call_user_func_array(
                            $config['connection']['class'] . '::factory',
                            $config['connection']['config']
                        );

                    /** @var \App\Services\DataSource\Interfaces\Mapper $mapper */
                    $mapper = call_user_func_array(
                        $config['mapper']['class'] . '::factory',
                        $config['mapper']['config']
                    );

                    $parser = call_user_func_array(
                        $config['parser']['class'] . '::factory',
                        $config['parser']['config']
                    );

                    $service
                        ->sync(
                            $network,
                            $path,
                            $connection,
                            $parser,
                            $mapper
                        );

                    $this->getOutput()->writeln('<info>done!</info>');
                }

            });

            $service->truncate(Setting::version());

        } catch (\Throwable $e) {

            $service->truncate(Setting::nextVersion());

            //  @todo (Pablo 2021-12-15) - Report error by email
            throw $e;
        }

        return Command::SUCCESS;
    }
}
