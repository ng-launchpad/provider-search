<?php

namespace App\Services;

use App\Models\Provider;
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
            $file = tmpfile();

            $connection->download($filename, $file);

            $collection = $parser->parse($file);

            $collection = $collection->map(fn(array $item) => $mapper->transform($item));

            //  @todo (Pablo 2021-12-10) - ensure we handle updates properly

            $collection->each(fn(Provider $provider) => $provider->save());

            //  @todo (Pablo 2021-12-08) - delete untouched items from the database

        } catch (\Throwable $e) {
            //  @todo (Pablo 2021-12-08) - handle failure
        }

        return $this;
    }
}
