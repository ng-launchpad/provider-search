<?php

namespace App\Console\Commands;

use App\Models\Provider;
use Illuminate\Console\Command;

class CleanProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-providers {--mode=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup duplicate providers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get cleanup mode
        $mode = $this->option('mode');
        if (!$mode) {
            $this->info('Please provide cleanup mode: --mode=strict|loose|npi');
            return 1;
        }

        // output to console
        $this->info('Cleaning duplicate providers...');

        // create progress bar
        $count = Provider::count();
        $bar = $this->output->createProgressBar($count);

        // iterate providers
        Provider::query()
            ->lazyById(1)
            ->each(function ($provider) use ($bar, $mode) {

                // advance progress bar
                $bar->advance();

                if ($provider->trashed()) {
                    return;
                }

                // find matching providers
                $matches = Provider::matching($provider, $mode)->get();

                // delete matching providers
                $matches->each->delete();
            });

        // finish progress bar
        $bar->finish();
        $this->newLine();

        return 0;
    }
}
