<?php

namespace App\Services\DataSource\Interfaces;

use App\Models\Network;
use Illuminate\Support\Collection;

interface Mapper
{
    public static function factory(): self;

    /**
     * Takes a Collection and extracts a Collection of Languages
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractLanguages(Collection $collection): Collection;

    /**
     * Takes a Collection and extracts a Collection of Locations
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractLocations(Collection $collection): Collection;

    /**
     * Takes a Collection and extracts a Collection of Specialities
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractSpecialities(Collection $collection): Collection;

    /**
     * Takes a Collection and extracts a Collection of Hospitals
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractHospitals(Collection $collection): Collection;

    /**
     * Takes a Collection and extracts a Collection of Specialities
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractProviders(Collection $collection): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderLocations
     *
     * @param Collection $collection
     * @param Network    $network
     *
     * @return Collection
     */
    public function extractProviderLocations(Collection $collection, Network $network): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderLanguages
     *
     * @param Collection $collection
     * @param Network    $network
     *
     * @return Collection
     */
    public function extractProviderLanguages(Collection $collection, Network $network): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderSpecialities
     *
     * @param Collection $collection
     * @param Network    $network
     *
     * @return Collection
     */
    public function extractProviderSpecialities(Collection $collection, Network $network): Collection;
}
