<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Services\DataSource\Interfaces\Connection;
use App\Services\DataSource\Interfaces\Mapper;
use App\Services\DataSource\Interfaces\Parser;

final class DataSourceService
{
    public static function factory()
    {
        return new self();
    }

    public function truncate(): self
    {
        Provider::truncate();
        Network::truncate();
        Language::truncate();
        Location::truncate();
        Speciality::truncate();

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
    public function sync(string $filename, Connection $connection, Mapper $mapper, Parser $parser): self
    {
        $file = tmpfile();

        $connection->download($filename, $file);

        $collection = $parser->parse($file);

        $collection = $collection->map(fn(array $item) => $mapper->transform($item));

        $collection->each(fn(Provider $provider) => $provider->save());

        return $this;
    }
}
