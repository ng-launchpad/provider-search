<?php

namespace App\Services\DataSource;

use App\Models\Hospital;
use App\Models\Language;
use App\Models\Location;
use App\Models\Network;
use App\Models\Provider;
use App\Models\Speciality;
use App\Models\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

abstract class Mapper implements Interfaces\Mapper
{
    protected State $texas;
    private         $version;
    private         $providerLocationCache = [];

    // `final` prevents PHPStan from reporting an unsafe usage of static() in factory()
    // https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static
    final public function __construct(State $texas = null)
    {
        $this->texas = $texas ?? State::findByCodeOrFail('TX');
    }

    public static function factory(State $texas = null): self
    {
        return new static($texas);
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function extractLanguages(array $row): Collection
    {
        $collection = new Collection();

        foreach ($this->getLanguageKeys() as $key) {

            $label = $row[$key] ?? null;

            if ($label && strtolower($label) !== 'english') {

                foreach (explode(',', $label) as $datum) {

                    $datum = trim($datum);

                    if ($datum) {

                        $language          = new Language();
                        $language->label   = $datum;
                        $language->version = $this->version;

                        $collection->add($language);
                    }
                }
            }
        }

        return $collection;
    }

    public function extractLocations(array $row): Collection
    {
        $collection = new Collection();

        $location = $this->buildLocation($row);

        if ($location->isDirty()) {
            $location->version = $this->version;
            $collection->add($location);
        }

        return $collection;
    }

    private function buildLocation($item): Location
    {
        $location = new Location();

        foreach ($this->getLocationKeys() as $property => $key) {

            $location->{$property} = $key instanceof \Closure
                ? $key($item)
                : $item[$key];
        }

        $location->hash = $location->generateHash();

        return $location;
    }

    public function extractSpecialities(array $row): Collection
    {
        $collection = new Collection();

        foreach ($this->getSpecialityKeys() as $key) {

            if (is_array($key)) {
                /**
                 * @var $key       string
                 * @var $formatter \Closure
                 */
                [$key, $formatter] = $key;
            }

            $label = $row[$key] ?? null;

            if (isset($formatter)) {
                $label = $formatter($label);
            }

            if ($label) {

                foreach (explode(',', $label) as $datum) {

                    $datum = trim($datum);

                    if ($datum) {

                        $speciality          = new Speciality();
                        $speciality->label   = $datum;
                        $speciality->version = $this->version;

                        $collection->add($speciality);
                    }
                }
            }
        }

        return $collection;
    }

    public function extractHospitals(array $row): Collection
    {
        $collection = new Collection();

        foreach ($this->getHospitalKeys() as $key) {

            $hospital = new Hospital();

            if (!empty($row[$key]) && strtolower($row[$key]) !== 'information not available') {
                $hospital->label = $row[$key];
            }

            if ($hospital->isDirty()) {
                $hospital->version = $this->version;
                $collection->add($hospital);
            }
        }

        return $collection;
    }

    public function extractProviders(array $row, Network $network): Collection
    {
        $collection = new Collection();

        $provider = new Provider();

        foreach ($this->getProviderKeys() as $property => $key) {

            $provider->{$property} = $key instanceof \Closure
                ? $key($row)
                : $row[$key];
        }

        if ($provider->isDirty()) {
            $provider->network_id = $network->id;
            $provider->version    = $this->version;
            $collection->add($provider);
        }

        return $collection;
    }

    public function extractProviderLocations(array $row, Network $network): Collection
    {
        $collection = new Collection();

        try {

            $provider = Provider::findByVersionNpiAndNetworkOrFail(
                $this->version,
                $row[$this->getProviderNpiKey()],
                $network
            );

            $location = $this->buildLocation($row);
            $location = Location::query()
                ->where('hash', $location->generateHash())
                ->firstOrFail();

            if (!array_key_exists($provider->id, $this->providerLocationCache)) {
                $this->providerLocationCache[$provider->id] = 0;
            }

            $collection->add([
                $provider,
                $location,
                // If this is the Provider's first address (cache is 0) then consider it their primary address
                !(bool) $this->providerLocationCache[$provider->id]++,
                $row[$this->getProviderPhoneKey()],
            ]);

        } catch (ModelNotFoundException $e) {
            // Didn't find provider or location, so continue
        }

        return $collection;
    }

    public function extractProviderLanguages(array $row, Network $network): Collection
    {
        $collection = new Collection();

        try {

            $provider  = Provider::findByVersionNpiAndNetworkOrFail(
                $this->version,
                $row[$this->getProviderNpiKey()],
                $network
            );
            $languages = $this->extractLanguages($row);

            foreach ($languages as $language) {
                try {

                    $language = Language::findByVersionAndLabelOrFail($provider->version, $language->label);
                    $collection->add([
                        $provider,
                        $language,
                    ]);

                } catch (ModelNotFoundException $e) {
                    // Didn't find language, so continue
                }
            }

        } catch (ModelNotFoundException $e) {
            // Didn't find provider, so continue
        }

        return $collection;
    }

    public function extractProviderSpecialities(array $row, Network $network): Collection
    {
        $collection = new Collection();

        try {

            $provider     = Provider::findByVersionNpiAndNetworkOrFail(
                $this->version,
                $row[$this->getProviderNpiKey()],
                $network
            );
            $specialities = $this->extractSpecialities($row);

            foreach ($specialities as $speciality) {
                try {

                    $speciality = Speciality::where('label', $speciality->label)->firstOrFail();
                    $collection->add([
                        $provider,
                        $speciality,
                    ]);

                } catch (ModelNotFoundException $e) {
                    // Didn't find speciality so continue
                }
            }

        } catch (ModelNotFoundException $e) {
            // Didn't find provider, so continue
        }

        return $collection;
    }

    public function extractProviderHospitals(array $row, Network $network): Collection
    {
        $collection = new Collection();

        try {

            $provider  = Provider::findByVersionNpiAndNetworkOrFail(
                $this->version,
                $row[$this->getProviderNpiKey()],
                $network
            );
            $hospitals = $this->extractHospitals($row);

            foreach ($hospitals as $hospital) {
                try {

                    $hospital = Hospital::where('label', $hospital->label)->firstOrFail();
                    $collection->add([
                        $provider,
                        $hospital,
                    ]);

                } catch (ModelNotFoundException $e) {
                    // Didn't find hospital, so continue
                }
            }
        } catch (ModelNotFoundException $e) {
            // Didn't find provider, so continue
        }

        return $collection;
    }

    protected abstract function getLanguageKeys(): array;

    protected abstract function getLocationKeys(): array;

    protected abstract function getSpecialityKeys(): array;

    protected abstract function getHospitalKeys(): array;

    protected abstract function getProviderKeys(): array;

    protected abstract function getProviderNpiKey(): string;

    protected abstract function getProviderPhoneKey(): string;
}
