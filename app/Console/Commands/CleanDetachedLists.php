<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CleanDetachedLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-detached-lists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup specialities, hospitals, languages and locations that are not attached to any provider.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->cleanList('hospitals');
        $this->cleanList('languages');
        $this->cleanList('specialities');
        $this->cleanList('locations');

        return 0;
    }

    /**
     * Cleanup a concrete list
     */
    public function cleanList($name)
    {
        // output to console
        $this->info('Cleaning detached ' . $name . '...');

        // define model class
        $model = 'App\\Models\\' . Str::ucfirst(Str::singular($name));

        // delete list that has no providers attached
        $deleted = $model::query()
            ->whereDoesntHave('providers')
            ->delete();

        // output to console
        $this->info($deleted . ' ' . $name . ' deleted');
    }
}
