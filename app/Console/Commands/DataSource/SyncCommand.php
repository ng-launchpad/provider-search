<?php

namespace App\Console\Commands\DataSource;

use App\Models\Network;
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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {

            Artisan::call('down');

            $service = DataSourceService::factory();

            DB::transaction(function () use ($service) {

                $service->truncate();

                $networks = $this->input->getOption('network')
                    ? [Network::getByLabelOrFail($this->input->getOption('network'))]
                    : Network::all();

                foreach ($networks as $network) {

                    try {

                        $this->getOutput()->write(sprintf(
                            'Syncing <comment>%s</comment> data... ',
                            $network->label
                        ));

                        $config     = $network->getConfig();
                        $path       = $config['path'];
                        $connection = call_user_func_array(
                            $config['connection']['class'] . '::factory',
                            $config['connection']['config']
                        );
                        $mapper     = call_user_func($config['mapper'] . '::factory');
                        $parser     = call_user_func($config['parser'] . '::factory');

                        $service
                            ->sync(
                                $network,
                                $path,
                                $connection,
                                $parser,
                                $mapper
                            );

                        $this->getOutput()->writeln('<info>done!</info>');

                    } catch (\Throwable $e) {
                        $this->getOutput()->writeln(sprintf(
                            '<error>ERROR: %s</error>',
                            $e->getMessage()
                        ));
                        throw $e;
                    }
                }

                $service->endSync();

            });

        } catch (\Throwable $e) {
            //  @todo (Pablo 2021-12-15) - Report error by email

        } finally {
            Artisan::call('up');
        }

        return Command::SUCCESS;
    }
}
