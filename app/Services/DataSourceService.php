<?php

namespace App\Services;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Notifications\SyncFailureNotification;
use App\Notifications\SyncSuccessNotification;
use App\Services\DataSource\Interfaces\Connection;
use App\Services\DataSource\Interfaces\Mapper;
use App\Services\DataSource\Interfaces\Parser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class DataSourceService
{
    const MIME_ZIP = 'application/zip';

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
     * @param \App\Models\Network                                    $network
     * @param string                                                 $path
     * @param \App\Services\DataSource\Interfaces\Connection         $connection
     * @param \App\Services\DataSource\Interfaces\Parser             $parser
     * @param \App\Services\DataSource\Interfaces\Mapper             $mapper
     * @param \Symfony\Component\Console\Output\OutputInterface|null $output
     *
     * @return $this
     */
    public function sync(Network $network, string $path, Connection $connection, Parser $parser, Mapper $mapper, OutputInterface $output = null): self
    {
        $file   = tmpfile();
        $output = $output ?? new NullOutput();

        // --------------------------------------------------------------------------

        try {

            $start = Carbon::now();
            $output->write('Beginning download... ');
            $connection->download($path, $file);
            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>, filesize: %s)',
                number_format($this->elapsed($start)),
                fstat($file)['size'] ?? 'unknown'
            ));

            // --------------------------------------------------------------------------

            if ($this->isZip($file)) {

                $output->write('Download is a zip, unzipping... ');

                $start       = Carbon::now();
                $newResource = $this->unzip($file);

                $output->writeln(sprintf(
                    '<comment>done</comment> (took <comment>%s seconds</comment>, filesize: %s bytes)',
                    number_format($this->elapsed($start)),
                    fstat($newResource)['size'] ?? 'unknown'
                ));

                //  Remove the old file in favour of the new file and clean up
                fclose($file);
                unlink($file);
                $file = $newResource;
            }

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Beginning parse... ');
            $collection = $parser->parse($file);
            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting languages... ');

            $temp = $mapper->extractLanguages($collection)->unique('label');

            $output->writeln(sprintf(
                '<comment>done</comment>, %s languages extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving languages... ');

            $temp->each(fn(Language $model) => $model->save());

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting locations... ');

            $temp = $mapper->extractLocations($collection)->unique('hash');

            $output->writeln(sprintf(
                '<comment>done</comment>, %s locations extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving locations... ');

            $temp->each(fn(Location $model) => $model->save());

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting specialities... ');

            $temp = $mapper->extractSpecialities($collection)->unique('label');

            $output->writeln(sprintf(
                '<comment>done</comment>, %s specialities extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving specialities... ');

            $temp->each(fn(Speciality $model) => $model->save());

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting hospitals... ');

            $temp = $mapper->extractHospitals($collection)->unique('label');

            $output->writeln(sprintf(
                '<comment>done</comment>, %s hospitals extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving hospitals... ');

            $temp->each(fn(Hospital $model) => $model->save());

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting providers... ');

            $temp = $mapper->extractProviders($collection)
                ->unique('npi')
                ->filter(fn(Provider $provider) => !empty($provider->npi));

            $output->writeln(sprintf(
                '<comment>done</comment>, %s providers extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving providers... ');

            $temp->each(function (Provider $model) use ($network) {
                $model->network_id = $network->id;
                $model->save();
            });

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting provider locations... ');

            $temp = $mapper->extractProviderLocations($collection, $network)
                ->unique(function (array $item) {
                    return sprintf(
                        '%s,%s,%s',
                        $item[0]->id,   //  Provider
                        $item[1]->id,   //  Location
                        $item[2]        //  is_primary
                    );
                });

            $output->writeln(sprintf(
                '<comment>done</comment>, %s provider locations extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving provider locations... ');

            $temp->each(function (array $set) {
                [$provider, $location, $is_primary] = $set;
                $provider->locations()->attach($location, ['is_primary' => $is_primary]);
            });

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting provider languages... ');

            $temp = $mapper->extractProviderLanguages($collection, $network)
                ->unique(function (array $item) {
                    return sprintf(
                        '%s,%s',
                        $item[0]->id,   //  Provider
                        $item[1]->id    //  Language
                    );
                });

            $output->writeln(sprintf(
                '<comment>done</comment>, %s provider languages extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving provider languages... ');

            $temp->each(function (array $set) {
                [$provider, $language] = $set;
                $provider->languages()->attach($language);
            });

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting provider specialities... ');

            $temp = $mapper->extractProviderSpecialities($collection, $network)
                ->unique(function (array $item) {
                    return sprintf(
                        '%s,%s',
                        $item[0]->id,   //  Provider
                        $item[1]->id    //  Speciality
                    );
                });

            $output->writeln(sprintf(
                '<comment>done</comment>, %s provider specialities extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving provider specialities... ');

            $temp->each(function (array $set) {
                [$provider, $speciality] = $set;
                $provider->specialities()->attach($speciality);
            });

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->write('Extracting provider hospitals... ');

            $temp = $mapper->extractProviderHospitals($collection, $network)
                ->unique(function (array $item) {
                    return sprintf(
                        '%s,%s',
                        $item[0]->id,   //  Provider
                        $item[1]->id    //  Hospital
                    );
                });

            $output->writeln(sprintf(
                '<comment>done</comment>, %s provider hospitals extracted (took <comment>%s seconds</comment>)',
                number_format($temp->count()),
                number_format($this->elapsed($start))
            ));

            $start = Carbon::now();
            $output->write('Saving provider hospitals... ');

            $temp->each(function (array $set) {
                [$provider, $hospital] = $set;
                $provider->hospitals()->attach($hospital);
            });

            $output->writeln(sprintf(
                '<comment>done</comment> (took <comment>%s seconds</comment>)',
                number_format($this->elapsed($start))
            ));

        } finally {
            //  @todo (Pablo 2022-02-14) - delete the downloaded file
        }
        // --------------------------------------------------------------------------

        return $this;
    }

    /**
     * Determines if the $file resource is a zip or not
     *
     * @param resource $resource The file to check
     *
     * @return bool
     */
    public function isZip($resource): bool
    {
        return mime_content_type($resource) === self::MIME_ZIP;
    }

    /**
     * Unzips $file
     *
     * @param resource $file The file to unzip
     *
     * @return resource
     */
    public function unzip($file)
    {
        $zip = new \ZipArchive();
        $zip->open(stream_get_meta_data($file)['uri']);

        $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'DataSource' . md5(microtime(true));

        $firstFile = $zip->getNameIndex(0);
        $zip->extractTo($tempDir);

        return fopen($tempDir . DIRECTORY_SEPARATOR . $firstFile, 'r');
    }

    public function notifySuccess(array $log)
    {
        Notification::route('mail', config('datasource.contact'))
            ->notify(new SyncSuccessNotification($log));
    }

    public function notifyError(\Throwable $e, array $log)
    {
        Notification::route('mail', config('datasource.contact'))
            ->notify(new SyncFailureNotification($e, $log));
    }

    private function elapsed(Carbon $start): float
    {
        return Carbon::now()->diffInSeconds($start);
    }

}
