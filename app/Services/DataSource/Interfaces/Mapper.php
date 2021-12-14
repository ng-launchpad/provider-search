<?php

namespace App\Services\DataSource\Interfaces;

use App\Models\Provider;
use Illuminate\Support\Collection;

interface Mapper
{
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
     * Takes a Collection and extracts a Collection of Networks
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractNetworks(Collection $collection): Collection;

    /**
     * Takes a Collection and extracts a Collection of Specialities
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractSpecialities(Collection $collection): Collection;

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
     *
     * @return Collection
     */
    public function extractProviderLocations(Collection $collection): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderLanguages
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractProviderLanguages(Collection $collection): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderSpecialities
     *
     * @param Collection $collection
     *
     * @return Collection
     */
    public function extractProviderSpecialities(Collection $collection): Collection;
}
