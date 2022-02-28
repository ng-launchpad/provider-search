<?php

namespace App\Services\DataSource\Interfaces;

use App\Models\Network;
use Illuminate\Support\Collection;

interface Mapper
{
    public static function factory(): self;

    /**
     * Determines whether the row should be skipped or not
     *
     * @param array $row
     *
     * @return bool
     */
    public function skipRow(array $row): bool;

    /**
     * Takes a data row and extracts a Collection of Languages
     *
     * @param array $row
     *
     * @return Collection
     */
    public function extractLanguages(array $row): Collection;

    /**
     * Takes a data row and extracts a Collection of Locations
     *
     * @param array $row
     *
     * @return Collection
     */
    public function extractLocations(array $row): Collection;

    /**
     * Takes a data row and extracts a Collection of Specialities
     *
     * @param array $row
     *
     * @return Collection
     */
    public function extractSpecialities(array $row): Collection;

    /**
     * Takes a data row and extracts a Collection of Hospitals
     *
     * @param array $row
     *
     * @return Collection
     */
    public function extractHospitals(array $row): Collection;

    /**
     * Takes a data row and extracts a Collection of Providers
     *
     * @param array   $row
     * @param Network $network
     *
     * @return Collection
     */
    public function extractProviders(array $row, Network $network): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderLocations
     *
     * @param array   $row
     * @param Network $network
     *
     * @return Collection
     */
    public function extractProviderLocations(array $row, Network $network): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderLanguages
     *
     * @param array   $row
     * @param Network $network
     *
     * @return Collection
     */
    public function extractProviderLanguages(array $row, Network $network): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderSpecialities
     *
     * @param array   $row
     * @param Network $network
     *
     * @return Collection
     */
    public function extractProviderSpecialities(array $row, Network $network): Collection;

    /**
     * Takes a Collection and extracts a Collection of ProviderHospitals
     *
     * @param array   $row
     * @param Network $network
     *
     * @return Collection
     */
    public function extractProviderHospitals(array $row, Network $network): Collection;
}
