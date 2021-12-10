<?php

namespace App\Services;

use App\Services\DataSource\Interfaces\Connection;
use App\Services\DataSource\Interfaces\Mapper;
use App\Services\DataSource\Interfaces\Parser;

final class DataSourceService
{
    public static function factory()
    {
        return new self();
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
    public function sync(string $filename, Connection $connection, Mapper $mapper, Parser $parser): self
    {
        try {

            //  @todo (Pablo 2021-12-08) - get a temporary file resource
            //  $file = tmpfile();

            //  @todo (Pablo 2021-12-08) - download the file
            //  $connection->download($filename, $file);

            //  @todo (Pablo 2021-12-08) - parse the file into an array/object
            //  $collection = $parser->parse($file);

            //  @todo (Pablo 2021-12-08) - transform the collection into an array of Providers using the mapper
            //  $collection = $collection->map(fn(array $item) => $mapper->transform($item));

            //  @todo (Pablo 2021-12-08) - sync (create/update) the collection into the database
            //  $collection->each(fn(Provider $provider) => $provider->save());

            //  @todo (Pablo 2021-12-08) - delete untouched items from the database

        } catch (\Throwable $e) {
            //  @todo (Pablo 2021-12-08) - handle failure
        }

        return $this;
    }
}
