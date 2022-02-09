<?php

namespace App\Services;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Notifications\SyncFailureNotification;
use App\Services\DataSource\Interfaces\Connection;
use App\Services\DataSource\Interfaces\Mapper;
use App\Services\DataSource\Interfaces\Parser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

final class DataSourceService
{
    public static function factory()
    {
        return new self();
    }

    public function truncate(int $version): self
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
            DB::statement("DELETE FROM `$table` WHERE `version` = ?;", [$version]);
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
            ->unique('label')
            ->each(fn(Language $model) => $model->save());

        $mapper->extractLocations($collection)
            ->unique('hash')
            ->each(fn(Location $model) => $model->save());

        $mapper->extractSpecialities($collection)
            ->unique('label')
            ->each(fn(Speciality $model) => $model->save());

        $mapper->extractHospitals($collection)
            ->unique('label')
            ->each(fn(Hospital $model) => $model->save());

        $mapper->extractProviders($collection)
            ->unique('npi')
            ->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

        $mapper->extractProviderLocations($collection, $network)
            ->unique(function ($item) {
                return sprintf(
                    '%s,%s,%s',
                    $item['location_id'],
                    $item['provider_id'],
                    $item['is_primary']
                );
            })
            ->each(function (array $set) {
                [$provider, $location, $is_primary] = $set;
                $provider->locations()->attach($location, ['is_primary' => $is_primary]);
            });

        $mapper->extractProviderLanguages($collection, $network)
            ->unique(function ($item) {
                return sprintf(
                    '%s,%s',
                    $item['language_id'],
                    $item['provider_id']
                );
            })
            ->each(function (array $set) {
                [$provider, $language] = $set;
                $provider->languages()->attach($language);
            });

        $mapper->extractProviderSpecialities($collection, $network)
            ->unique(function ($item) {
                return sprintf(
                    '%s,%s',
                    $item['provider_id'],
                    $item['speciality_id']
                );
            })
            ->each(function (array $set) {
                [$provider, $speciality] = $set;
                $provider->specialities()->attach($speciality);
            });

        $mapper->extractProviderHospitals($collection, $network)
            ->unique(function ($item) {
                return sprintf(
                    '%s,%s',
                    $item['hospital_id'],
                    $item['provider_id']
                );
            })
            ->each(function (array $set) {
                [$provider, $hospital] = $set;
                $provider->hospitals()->attach($hospital);
            });

        return $this;
    }

    public function notifyError(\Throwable $e)
    {
        Notification::route('mail', config('datasource.contact'))
            ->notify(new SyncFailureNotification($e));
    }
}
