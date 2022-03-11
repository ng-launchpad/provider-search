<?php

namespace App\Services;

use App\Helper\BytesForHumans;
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
use Symfony\Component\Console\Output\ConsoleOutputInterface;
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
            $output->writeln(sprintf(
                'Beginning download <comment>%s</comment>... ',
                $path
            ));
            $connection->download($path, $file);
            $output->writeln(sprintf(
                '➞ <comment>done</comment> (took <comment>%s seconds</comment>, filesize: %s)',
                number_format($this->elapsed($start)),
                BytesForHumans::fromBytes(fstat($file)['size'] ?? -1)
            ));

            // --------------------------------------------------------------------------

            if ($this->isZip($file)) {

                $output->writeln('Download is a zip, unzipping... ');

                $start       = Carbon::now();
                $newResource = $this->unzip($file);

                $output->writeln(sprintf(
                    '➞ <comment>done</comment> (took <comment>%s seconds</comment>, filesize: %s)',
                    number_format($this->elapsed($start)),
                    BytesForHumans::fromBytes(fstat($file)['size'] ?? -1)
                ));

                //  Remove the old file in favour of the new file and clean up
                fclose($file);
                $file = $newResource;
            }

            // --------------------------------------------------------------------------

            $start = Carbon::now();
            $output->writeln('Beginning data extraction... ');

            if ($output instanceof ConsoleOutputInterface) {
                $section = $output->section();
            }

            foreach ($parser->parse($file) as $index => $row) {

                if ($mapper->skipRow($row)) {
                    continue;
                }

                //  Extract languages
                $collection = $mapper->extractLanguages($row)
                    ->unique('label');

                $collection->each(function (Language $language) {
                    if (!$language->existsForVersion()) {
                        $language->save();
                    }
                });

                // --------------------------------------------------------------------------

                //  Extract locations
                $collection = $mapper->extractLocations($row)
                    ->unique('hash');

                $collection->each(function (Location $location) {
                    if (!$location->existsForVersion()) {
                        $location->save();
                    }
                });

                // --------------------------------------------------------------------------

                //  Extract specialities
                $collection = $mapper->extractSpecialities($row)
                    ->unique('label');

                $collection->each(function (Speciality $speciality) {
                    if (!$speciality->existsForVersion()) {
                        $speciality->save();
                    }
                });

                // --------------------------------------------------------------------------

                //  Extract hospitals
                $collection = $mapper->extractHospitals($row)
                    ->unique('label');

                $collection->each(function (Hospital $hospital) {
                    if (!$hospital->existsForVersion()) {
                        $hospital->save();
                    }
                });

                // --------------------------------------------------------------------------

                //  Extract providers
                $collection = $mapper->extractProviders($row, $network)
                    ->unique('npi')
                    ->filter(fn(Provider $provider) => !empty($provider->npi));

                $collection->each(function (Provider $provider) {
                    if (!$provider->existsForVersionAndNetwork()) {
                        $provider->save();
                    }
                });

                // --------------------------------------------------------------------------

                //  Extract provider locations
                $collection = $mapper->extractProviderLocations($row, $network)
                    ->unique(function (array $item) {
                        return sprintf(
                            '%s,%s,%s,%s',
                            $item[0]->id,   //  Provider
                            $item[1]->id,   //  Location
                            $item[2],       //  is_primary
                            $item[3]        //  phone
                        );
                    });

                $collection->each(function (array $set) {
                    [$provider, $location, $is_primary, $phone] = $set;
                    $provider
                        ->locations()
                        ->syncWithPivotValues(
                            $location,
                            [
                                'is_primary' => $is_primary,
                                'phone'      => $phone,
                            ],
                            false
                        );
                });

                // --------------------------------------------------------------------------

                //  Extract provider languages
                $collection = $mapper->extractProviderLanguages($row, $network)
                    ->unique(function (array $item) {
                        return sprintf(
                            '%s,%s',
                            $item[0]->id,   //  Provider
                            $item[1]->id    //  Language
                        );
                    });

                $collection->each(function (array $set) {
                    [$provider, $language] = $set;
                    $provider
                        ->languages()
                        ->syncWithoutDetaching($language);
                });

                // --------------------------------------------------------------------------

                //  Extract provider specialities
                $collection = $mapper->extractProviderSpecialities($row, $network)
                    ->unique(function (array $item) {
                        return sprintf(
                            '%s,%s',
                            $item[0]->id,   //  Provider
                            $item[1]->id    //  Speciality
                        );
                    });

                $collection->each(function (array $set) {
                    [$provider, $speciality] = $set;
                    $provider
                        ->specialities()
                        ->syncWithoutDetaching($speciality);
                });

                // --------------------------------------------------------------------------

                //  Extract provider hospitals
                $collection = $mapper->extractProviderHospitals($row, $network)
                    ->unique(function (array $item) {
                        return sprintf(
                            '%s,%s',
                            $item[0]->id,   //  Provider
                            $item[1]->id    //  Hospital
                        );
                    });

                $collection->each(function (array $set) {
                    [$provider, $hospital] = $set;
                    $provider
                        ->hospitals()
                        ->syncWithoutDetaching($hospital);
                });

                // --------------------------------------------------------------------------

                if (isset($section)) {
                    $section->overwrite(sprintf(
                        implode(PHP_EOL, [
                            '➞ Processed line <comment>%s</comment>',
                            '  memory usage <comment>%s</comment>',
                            '  time elapsed <comment>%s</comment> seconds',
                        ]),
                        number_format($index),
                        BytesForHumans::fromBytes(memory_get_usage()),
                        number_format($this->elapsed($start))
                    ));
                }
            }

            $output->writeln(sprintf(
                '➞ <comment>done</comment>, %s lines processed (took <comment>%s seconds</comment>)',
                number_format($index ?? 0),
                number_format($this->elapsed($start))
            ));

        } finally {
            if ($file !== null) {
                fclose($file);
            }
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
