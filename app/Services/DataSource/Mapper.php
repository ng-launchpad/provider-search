<?php

namespace App\Services\DataSource;

use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use Illuminate\Support\Collection;

abstract class Mapper implements Interfaces\Mapper
{
    static $providerLocationCache = [];

    // `final` prevents PHPStan from reporting an unsafe usage of static() in factory()
    // https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static
    final public function __construct()
    {
    }

    public static function factory(): self
    {
        return new static();
    }

    public function extractLanguages(Collection $collection): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut) {
            foreach ($this->getLanguageKeys() as $key) {

                $language = new Language();

                if (!empty($item[$key]) && strtolower($item[$key]) !== 'english') {
                    $language->label = $item[$key];
                }

                if ($language->isDirty()) {
                    $collectionOut->add($language);
                }
            }
        });

        return $collectionOut;
    }

    public function extractLocations(Collection $collection): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut) {

            $location = $this->buildLocation($item);

            if ($location->isDirty()) {
                $collectionOut->add($location);
            }
        });

        return $collectionOut;
    }

    private function buildLocation($item): Location
    {
        $location = new Location();

        foreach ($this->getLocationKeys() as $property => $key) {

            $location->{$property} = $key instanceof \Closure
                ? $key($item)
                : $item[$key];
        }

        $location->hash = $location->hash();

        return $location;
    }

    public function extractSpecialities(Collection $collection): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut) {
            foreach ($this->getSpecialityKeys() as $key) {

                $speciality = new Speciality();

                if ($item[$key] ?? null) {
                    $speciality->label = $item[$key];
                }

                if ($speciality->isDirty()) {
                    $collectionOut->add($speciality);
                }
            }
        });

        return $collectionOut;
    }

    public function extractProviders(Collection $collection): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut) {

            $provider = new Provider();

            foreach ($this->getProviderKeys() as $property => $key) {

                $provider->{$property} = $key instanceof \Closure
                    ? $key($item)
                    : $item[$key];
            }

            if ($provider->isDirty()) {
                $collectionOut->add($provider);
            }
        });

        return $collectionOut;
    }

    public function extractProviderLocations(Collection $collection, Network $network): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut, $network) {

            $provider = Provider::findByNpiAndNetworkOrFail($item['NPI'], $network);
            $location = $this->buildLocation($item);
            $location = Location::query()->where('hash', $location->hash())->firstOrFail();

            if (!array_key_exists($provider->id, static::$providerLocationCache)) {
                static::$providerLocationCache[$provider->id] = 0;
            }

            $collectionOut->add([
                $provider,
                $location,
                // If this is the Provider's first address (cache is 0) then consider it their primary address
                !(bool) static::$providerLocationCache[$provider->id]++,
            ]);

        });

        return $collectionOut;
    }

    public function extractProviderLanguages(Collection $collection, Network $network): Collection
    {
        //  @todo (Pablo 2021-12-14) - complete this
        return new Collection();
    }

    public function extractProviderSpecialities(Collection $collection, Network $network): Collection
    {
        //  @todo (Pablo 2021-12-14) - complete this
        return new Collection();
    }

    protected abstract function getLanguageKeys(): array;

    protected abstract function getLocationKeys(): array;

    protected abstract function getSpecialityKeys(): array;

    protected abstract function getProviderKeys(): array;
}
