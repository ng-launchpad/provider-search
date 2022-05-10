<?php

namespace App\Console\Commands;

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

        return 0;
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

                // transform stdClass to an array
                $aetna = collect($aetna)->toArray();

                // advance progress bar
                $bar->advance();

                // skip if same model already exists
                $exists = $model::matching($aetna)->exists();
                if ($exists) {
                    return;
                }

                // save new model
                $instance = new $model();
                $instance->fill($aetna);
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
