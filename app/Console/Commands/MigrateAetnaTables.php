<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Provider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateAetnaTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-aetna';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate AETNA tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->migrateList('hospitals');
        $this->migrateList('languages');
        $this->migrateList('specialities');
        $this->migrateList('locations');

        $this->migrateProviders();

        return 0;
    }

    public function migrateProviders()
    {
        // output to console
        $this->info('Migrating providers...');

        // create progress bar
        $count = DB::table('aetna_providers')->count();
        $bar = $this->output->createProgressBar($count);

        // collect created items
        $created = collect();

        // fetch aetna items row by row
        DB::table('aetna_providers')
            ->orderBy('id')
            ->lazy()
            ->each(function ($aetna) use ($bar, $created) {

                // advance progress bar
                $bar->advance();

                // check for unique keys
                $provider = Provider::unique($aetna)->first();

                // create provider if not exists
                if ($provider == null) {
                    $provider = new Provider();
                    $provider->fill(collect($aetna)->toArray());
                    $provider->save();

                    // add created provider to collection
                    $created->push($provider);
                }

                // migrate provider pivots
                $this->migratePivots($provider, 'hospitals', $aetna->id);
                $this->migratePivots($provider, 'languages', $aetna->id);
                $this->migratePivots($provider, 'specialities', $aetna->id);
                $this->migrateLocationPivots($provider, $aetna->id);
            });

        // finish progress bar
        $bar->finish();
        $this->newLine();
        $this->info($created->count(). ' new providers created');
    }

    public function migratePivots($provider, $relation, $aetnaId)
    {
        // define model name
        $model = 'App\\Models\\'.Str::ucfirst(Str::singular($relation));

        // define foreign key and aetna pivots table name
        $foreignKey = Str::singular($relation).'_id';
        $table = Str::singular($relation).'_provider';
        if ($relation == 'specialities') {
            $table = 'provider_speciality';
        }

        // select pivots
        $pivots = DB::table('aetna_'.$table)
            ->where('provider_id', $aetnaId)
            ->get();

        // select aetna instances
        $aetnas = DB::table('aetna_'.$relation)
            ->whereIn('id', $pivots->pluck($foreignKey))
            ->get();

        // find matching instance and attach
        foreach ($aetnas as $aetna) {
            $instance = $model::matching($aetna)->first();
            $provider->$relation()->attach($instance);
        }
    }

    public function migrateLocationPivots($provider, $aetnaId)
    {
        // find provider location pivots
        $pivots = DB::table('aetna_location_provider')
            ->where('provider_id', $aetnaId)
            ->get()
            ->keyBy('provider_id');

        // find all locations
        $aetnaLocations = DB::table('aetna_locations')
            ->whereIn('id', $pivots->pluck('location_id'))
            ->get();

        // iterate aetna locations
        foreach ($aetnaLocations as $aetnaLocation) {

            // find matching location and attach to provider
            $location = Location::matching($aetnaLocation)->first();
            $provider->locations()->attach($location, [
                'is_primary' => $pivots[$aetnaId]->is_primary,
            ]);
        }
    }

    public function migrateList($name)
    {
        // output to console
        $this->info('Migrating '.$name.'...');

        // create progress bar
        $count = DB::table('aetna_'.$name)->count();
        $bar = $this->output->createProgressBar($count);

        // define model name
        $model = 'App\\Models\\'.Str::ucfirst(Str::singular($name));

        // collect created items
        $created = collect();

        // fetch aetna items row by row
        DB::table('aetna_'.$name)
            ->orderBy('id')
            ->lazy()
            ->each(function ($aetna) use ($model, $bar, $created) {

                // advance progress bar
                $bar->advance();

                // skip if same model already exists
                $exists = $model::matching($aetna)->exists();
                if ($exists) {
                    return;
                }

                // save new model
                $instance = new $model();
                $instance->fill(collect($aetna)->toArray());
                $instance->save();

                // add created instance to collection
                $created->push($instance);
            });

        // finish progress bar
        $bar->finish();
        $this->newLine();

        // show table of created items
        if ($created->count() > 0) {
            $this->info($created->count(). ' new '.$name.' created');
            $this->table(
                ['ID', 'Label'],
                $created->map->only(['id', 'label'])->toArray()
            );
        }
    }
}
