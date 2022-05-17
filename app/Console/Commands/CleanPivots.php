<?php

namespace App\Console\Commands;

use App\Models\Provider;
use Illuminate\Console\Command;

class CleanPivots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-pivots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup provider pivot tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // output to console
        $this->info('Cleaning pivots...');

        // relations array
        $relations = ['hospitals', 'languages', 'specialities'];

        // count pivots before
        $pivots = collect($relations)->map(function ($label) {
            $table = Provider::make()->$label()->getTable();
            return [
                'label' => $label,
                'before' => \DB::table($table)->count(),
            ];
        });

        // create progress bar
        $count = Provider::count();
        $bar = $this->output->createProgressBar($count);

        Provider::query()
            ->orderBy('id')
            ->with([
                'hospitals',
                'languages',
                'specialities',
                'primary_locations',
                'secondary_locations',
            ])
            ->lazy()
            ->each(function ($provider) use ($bar) {

                // advance progress bar
                $bar->advance();

                // cleanup pivots
                $this->cleanPivot('hospitals', $provider);
                $this->cleanPivot('languages', $provider);
                $this->cleanPivot('specialities', $provider);
                $this->cleanPivot('secondary_locations', $provider);

                $this->cleanLocations($provider);
            });

        // finish progress bar
        $bar->finish();
        $this->newLine();

        // count pivots after
        $pivots = $pivots->map(function ($pivot) {
            $relation = $pivot['label'];
            $table = Provider::make()->$relation()->getTable();
            $pivot['after'] = \DB::table($table)->count();
            $pivot['diff'] = $pivot['before'] - $pivot['after'];
            return $pivot;
        });

        // show results table
        $this->table(
            ['Label', 'Before', 'After', 'Diff'],
            $pivots->toArray()
        );

        return 0;
    }

    public function cleanPivot($relation, $provider)
    {
        // get unique relation items
        $items = $provider->$relation;
        $unique = $items->unique();

        // don't proceed if all items are unique
        if ($items->count() == $unique->count()) {
            return;
        }

        // cleanup relation
        $provider->$relation()->sync([]);
        $provider->$relation()->attach($unique);
    }

    public function cleanLocations($provider)
    {
        // get unique primary locations
        $items = $provider->primary_locations;
        $primaryLocations = $items->unique();

        // get unique secondary locations
        $items = $provider->secondary_locations;
        $secondaryLocations = $items->unique();

        // cleanup relation
        $provider->locations()->sync([]);
        $provider->locations()->attach($primaryLocations, ['is_primary' => true]);
        $provider->locations()->attach($secondaryLocations);
    }
}
