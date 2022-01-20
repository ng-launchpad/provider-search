<?php

namespace App\Services\DataSource;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use Illuminate\Support\Collection;

abstract class Mapper implements Interfaces\Mapper
{
    private $providerLocationCache = [];

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

    public function extractHospitals(Collection $collection): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut) {
            foreach ($this->getHospitalKeys() as $key) {

                $speciality = new Hospital();

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

            $provider = Provider::findByNpiAndNetworkOrFail(
                $item[$this->getProviderNpiKey()],
                $network
            );
            $location = $this->buildLocation($item);
            $location = Location::query()->where('hash', $location->hash())->firstOrFail();

            if (!array_key_exists($provider->id, $this->providerLocationCache)) {
                $this->providerLocationCache[$provider->id] = 0;
            }

            $collectionOut->add([
                $provider,
                $location,
                // If this is the Provider's first address (cache is 0) then consider it their primary address
                !(bool) $this->providerLocationCache[$provider->id]++,
            ]);

        });

        return $collectionOut;
    }

    public function extractProviderLanguages(Collection $collection, Network $network): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut, $network) {

            $provider  = Provider::findByNpiAndNetworkOrFail(
                $item[$this->getProviderNpiKey()],
                $network
            );
            $languages = $this->extractLanguages(Collection::make([$item]));

            foreach ($languages as $language) {
                $language = Language::where('label', $language->label)->firstOrFail();
                $collectionOut->add([
                    $provider,
                    $language,
                ]);
            }
        });

        return $collectionOut;
    }

    public function extractProviderSpecialities(Collection $collection, Network $network): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut, $network) {

            $provider     = Provider::findByNpiAndNetworkOrFail(
                $item[$this->getProviderNpiKey()],
                $network
            );
            $specialities = $this->extractSpecialities(Collection::make([$item]));

            foreach ($specialities as $speciality) {
                $speciality = Speciality::where('label', $speciality->label)->firstOrFail();
                $collectionOut->add([
                    $provider,
                    $speciality,
                ]);
            }
        });

        return $collectionOut;
    }

    public function extractProviderHospitals(Collection $collection, Network $network): Collection
    {
        $collectionOut = new Collection();

        $collection->each(function ($item) use ($collectionOut, $network) {

            $provider  = Provider::findByNpiAndNetworkOrFail(
                $item[$this->getProviderNpiKey()],
                $network
            );
            $hospitals = $this->extractHospitals(Collection::make([$item]));

            foreach ($hospitals as $hospital) {
                $hospital = Hospital::where('label', $hospital->label)->firstOrFail();
                $collectionOut->add([
                    $provider,
                    $hospital,
                ]);
            }
        });

        return $collectionOut;
    }

    protected abstract function getLanguageKeys(): array;

    protected abstract function getLocationKeys(): array;

    protected abstract function getSpecialityKeys(): array;

    protected abstract function getHospitalKeys(): array;

    protected abstract function getProviderKeys(): array;

    protected abstract function getProviderNpiKey(): string;
}
