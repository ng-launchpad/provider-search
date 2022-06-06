<?php

namespace App\Console\Commands\DataSource;

use App\Models\Network;
use App\Models\Setting;
use App\Services\DataSource\Connection;
use App\Services\DataSource\Console\Output;
use App\Services\DataSourceService;
use Carbon\Carbon;
use Illuminate\Console\Command;
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

    private array $log = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->addOption('network', 'N', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Specify a network to sync; <network>:<file>');
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Throwable
     */
    public function handle()
    {
        $service  = DataSourceService::factory();
        $output   = new Output();
        $jobStart = Carbon::now();

        $output->writeln(sprintf(
            'Job started at <comment>%s</comment>',
            $jobStart->toIso8601String()
        ));

        $networks = $this->getNetworks();

        try {

            DB::transaction(function () use ($service, $output, $networks) {
                foreach ($networks as $networkSet) {

                    /**
                     * @var Network     $network
                     * @var string|null $file
                     */
                    [$network, $file] = $networkSet;

                    if (!$network->isEnabled()) {
                        $output->writeln('Network is not enabled, skipping');
                        continue;
                    }

                    $output->writeln('');
                    $output->writeln(sprintf(
                        'Syncing <comment>%s</comment> data... ',
                        $network->label
                    ));

                    $networkStart = Carbon::now();
                    $output->writeln(sprintf(
                        'Network sync started at <comment>%s</comment>',
                        $networkStart->toIso8601String()
                    ));

                    $config = $network->getConfig();
                    $path   = $config['path'];

                    $connection = $file
                        ? Connection\Local::factory($file)
                        : call_user_func_array(
                            $config['connection']['class'] . '::factory',
                            $config['connection']['config']
                        );

                    $output->writeln('Using Connection: <comment>' . get_class($connection) . '</comment>');

                    /** @var \App\Services\DataSource\Interfaces\Mapper $mapper */
                    $mapper = call_user_func_array(
                        $config['mapper']['class'] . '::factory',
                        $config['mapper']['config']
                    );

                    $mapper->setVersion(Setting::nextVersion());

                    $output->writeln('Using Mapper: <comment>' . get_class($mapper) . '</comment>');

                    $parser = call_user_func_array(
                        $config['parser']['class'] . '::factory',
                        $config['parser']['config']
                    );

                    $output->writeln('Using Parser: <comment>' . get_class($parser) . '</comment>');

                    $service
                        ->sync(
                            $network,
                            $path,
                            $connection,
                            $parser,
                            $mapper,
                            $output
                        );

                    $output->writeln(sprintf(
                        'Network sync took <info>%s</info> seconds',
                        $this->elapsed($networkStart)
                    ));

                    $output->writeln(sprintf(
                        'Network sync ended at <comment>%s</comment> (took <comment>%s</comment> seconds)',
                        $networkStart->toIso8601String(),
                        number_format($this->elapsed($networkStart))
                    ));
                    $output->writeln('');
                }
            });

            $truncateStart = Carbon::now();
            $output->writeln('Truncating old data... ');
            foreach ($networks as $networkSet) {

                /**
                 * @var Network $network
                 */
                [$network] = $networkSet;

                if ($network->isEnabled()) {
                    $service->truncate(Setting::version(), $network);
                }
            }
            $output->writeln(sprintf(
                '➞ <comment>done</comment> (took %s seconds)',
                number_format($this->elapsed($truncateStart))
            ));

            $output->writeln('Bumping version number... ');
            Setting::bumpVersion();
            $output->writeln('➞ <comment>done</comment>');

            $output->writeln('Sending success notification... ');
            $service->notifySuccess($output->getLog());
            $output->writeln('➞ <comment>done</comment>');

        } catch (\Throwable $e) {

            $output->writeln(sprintf(
                '<error>Error caught: %s</error>',
                $e->getMessage()
            ));
            $truncateStart = Carbon::now();
            $output->writeln('Truncating new data... ');
            foreach ($networks as $networkSet) {

                /**
                 * @var Network $network
                 */
                [$network] = $networkSet;
                if ($network->isEnabled()) {
                    $service->truncate(Setting::nextVersion(), $network);
                }
            }
            $output->writeln(sprintf(
                '➞ <comment>done</comment> (took %s seconds)',
                number_format($this->elapsed($truncateStart))
            ));

            $output->writeln('Sending failure notification... ');
            $service->notifyError($e, $output->getLog());
            $output->writeln('➞ <comment>done</comment>');

            $output->writeln('Re-throwing exception');
            throw $e;
        }

        $output->writeln(sprintf(
            'Job ended at <comment>%s</comment> (took <comment>%s</comment> seconds)',
            $jobStart->toIso8601String(),
            number_format($this->elapsed($jobStart))
        ));

        return self::SUCCESS;
    }

    private function elapsed(Carbon $start): float
    {
        return Carbon::now()->diffInSeconds($start);
    }

    private function getNetworks(): array
    {
        if ($this->input->getOption('network')) {

            $networks = [];

            foreach ($this->input->getOption('network') as $network) {

                preg_match('/^([a-zA-Z]+)(:(.*))?$/', $network, $matches);

                $networks[] = [
                    Network::getByLabelOrFail($matches[1] ?? ''),
                    $matches[3] ?? null,
                ];
            }

        } else {
            $networks = array_map(function (Network $network) {
                return [
                    $network,
                    null,
                ];
            }, Network::all());
        }

        return $networks;
    }
}
