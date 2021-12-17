<?php

namespace App\Services;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Services\DataSource\Interfaces\Connection;
use App\Services\DataSource\Interfaces\Mapper;
use App\Services\DataSource\Interfaces\Parser;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

final class DataSourceService
{
    public static function factory()
    {
        return new self();
    }

    public function truncate(): self
    {
        $models = [
            Provider::class,
            Hospital::class,
            Language::class,
            Location::class,
            Speciality::class,
        ];

        foreach ($models as $class) {

            /** @var string $table */
            $table = call_user_func([$class, 'getTableName']);
            DB::statement("DELETE FROM `$table`;");

            //  SQLite (testing) uses different syntax for resetting ID
            if (DB::connection() instanceof SQLiteConnection) {
                DB::statement("UPDATE `sqlite_sequence` SET `seq` = 0 WHERE `name` = '$table';");
            } else {
                DB::statement("ALTER TABLE `$table` AUTO_INCREMENT = 1;");
            }
        }

        return $this;
    }

    /**
     * Orchestrates the sync
     *
     * @param string     $filename
     * @param Connection $connection
     * @param Mapper     $mapper
     * @param Parser     $parser
     *
     * @return $this
     */
    public function sync(Network $network, string $path, Connection $connection, Parser $parser, Mapper $mapper): self
    {
        $file = tmpfile();

        $connection->download($path, $file);

        $collection = $parser->parse($file);

        $mapper->extractLanguages($collection)
            ->unique()
            ->each(fn(Language $model) => $model->save());

        $mapper->extractLocations($collection)
            ->unique()
            ->each(fn(Location $model) => $model->save());

        $mapper->extractSpecialities($collection)
            ->unique()
            ->each(fn(Speciality $model) => $model->save());

        $mapper->extractHospitals($collection)
            ->unique()
            ->each(fn(Hospital $model) => $model->save());

        $mapper->extractProviders($collection)
            ->unique()
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        $mapper->extractProviderLocations($collection, $network)
            ->unique()
            ->each(function (array $set) {
                [$provider, $location, $is_primary] = $set;
                $provider->locations()->attach($location, ['is_primary' => $is_primary]);
            });

        $mapper->extractProviderLanguages($collection, $network)
            ->unique()
            ->each(function (array $set) {
                [$provider, $language] = $set;
                $provider->languages()->attach($language);
            });

        $mapper->extractProviderSpecialities($collection, $network)
            ->unique()
            ->each(function (array $set) {
                [$provider, $speciality] = $set;
                $provider->specialities()->attach($speciality);
            });

        return $this;
    }
}
