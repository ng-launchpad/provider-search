<?php

namespace App\Models;

use App\Helper\PeopleMap;
use App\ModelPivots\LocationProviderPivot;
use App\Models\Concerns\HasGetTableName;
use App\Models\Concerns\HasVersionScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Provider
 *
 * @property int                                                                    $id
 * @property string                                                                 $label
 * @property \Illuminate\Support\Carbon|null                                        $created_at
 * @property \Illuminate\Support\Carbon|null                                        $updated_at
 * @method static \Database\Factories\ProviderFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider query()
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Provider filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Provider whereLike(string $column, string $value, string $boolean = 'and')
 * @property int                                                                    $state_id
 * @property-read \App\Models\State                                                 $state
 * @method static Builder|Provider whereStateId($value)
 * @method static Builder|Provider withKeywords(string $keywords)
 * @method static Builder|Provider withState(\App\Models\State $state)
 * @property string                                                                 $npi
 * @property string|null                                                            $phone
 * @property string|null                                                            $degree
 * @property string|null                                                            $website
 * @property string|null                                                            $gender
 * @property int|null                                                               $network_id
 * @property int                                                                    $is_facility
 * @property int                                                                    $is_accepting_new_patients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[]   $locations
 * @property-read int|null                                                          $locations_count
 * @property-read \App\Models\Network|null                                          $network
 * @method static Builder|Provider whereDegree($value)
 * @method static Builder|Provider whereGender($value)
 * @method static Builder|Provider whereIsAcceptingNewPatients($value)
 * @method static Builder|Provider whereIsFacility($value)
 * @method static Builder|Provider whereNetworkId($value)
 * @method static Builder|Provider whereNpi($value)
 * @method static Builder|Provider wherePhone($value)
 * @method static Builder|Provider whereWebsite($value)
 * @property string|null                                                            $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language[]   $languages
 * @property-read int|null                                                          $languages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Speciality[] $specialities
 * @property-read int|null                                                          $specialities_count
 * @method static Builder|Provider whereType($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Hospital[]   $hospitals
 * @property-read int|null                                                          $hospitals_count
 * @method static Builder|Provider withNetwork(\App\Models\Network $network)
 * @method static Builder|Provider withScope($type)
 * @method static Builder|Provider withType($type)
 * @method static Builder|Provider withVersion()
 * @property int                                                                    $version
 * @method static Builder|Provider whereVersion($value)
 * @property-read mixed                                                             $people
 * @method static Builder|Provider facility(bool $is_facility = true)
 * @property-read mixed                                                             $speciality_groups
 * @method static Builder|Provider withHospitals(string $hospital)
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static Builder|Provider matching($provider, $mode = 'strict')
 * @method static \Illuminate\Database\Query\Builder|Provider onlyTrashed()
 * @method static Builder|Provider unique($item)
 * @method static Builder|Provider whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Provider withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Provider withoutTrashed()
 */
class Provider extends Model
{
    use HasFactory, Filterable, HasGetTableName, SoftDeletes;

    const GENDER_MALE   = 'MALE';
    const GENDER_FEMALE = 'FEMALE';

    const SCOPE_DEFAULT    = 'ALL';
    const SCOPE_CITY       = 'CITY';
    const SCOPE_SPECIALITY = 'SPECIALITY';
    const SCOPE_LANGUAGE   = 'LANGUAGE';

    protected $casts = [
        'is_facility'               => 'boolean',
        'is_accepting_new_patients' => 'boolean',
    ];

    protected $fillable = [
        'version',
        'npi',
        'label',
        'gender',
        'is_facility',
        'is_accepting_new_patients',
        'network_id',
    ];

    /**
     * Gets the Network associated with the Provider
     */
    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Gets the Locations associated with the Provider
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class)
            ->using(LocationProviderPivot::class)
            ->withPivot([
                'is_primary',
            ]);
    }

    /**
     * Gets the Locations associated with the Provider
     */
    public function primary_locations()
    {
        return $this->locations()
            ->wherePivot('is_primary', true);
    }

    /**
     * Gets the Locations associated with the Provider
     */
    public function secondary_locations()
    {
        return $this->locations()
            ->wherePivot('is_primary', false);
    }

    /**
     * Gets the Languages associated with the Provider
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    /**
     * Gets the Specialities associated with the Provider
     */
    public function specialities()
    {
        return $this->belongsToMany(Speciality::class);
    }

    /**
     * Gets the Hospitals associated with the Provider
     */
    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class);
    }

    /**
     * Get list of people sharing the same location
     */
    public function getSpecialityGroupsAttribute()
    {
        if (!$this->is_facility) {
            return [];
        }

        // find list of people that share same locations
        $people = self::query()
            ->facility(false)
            ->withHospitals($this->label)
            ->whereHas('specialities')
            ->with('specialities')
            ->get();

        // prepare array of groups
        $groups = [];
        foreach (PeopleMap::getGroups() as $group_label) {
            $groups[$group_label] = [
                'label'  => $group_label,
                'people' => collect(),
            ];
        }

        foreach ($people as $human) {

            // iterate human specialities
            foreach ($human->specialities->unique() as $speciality) {

                // skip when speciality is not present in the mapping
                if (!isset(PeopleMap::MAP[$speciality->label])) {
                    continue;
                }

                // define target group label
                $group_label = PeopleMap::MAP[$speciality->label];

                // add human to the group
                $groups[$group_label]['people']->add($human->withoutRelations());
            }
        }

        // return groups as collection
        return collect(array_values($groups))

            // map every group into a class
            ->map(function ($data) {

                $data['people'] = $data['people']
                    ->unique('id')
                    ->sortBy('label');

                $group         = new \stdClass();
                $group->label  = $data['label'];
                $group->people = $data['people'];

                return $group;
            });
    }

    /**
     * Scope providers by human/facility
     */
    public function scopeFacility(Builder $query, bool $is_facility = true)
    {
        $query->where('is_facility', $is_facility);
    }

    /**
     * Scope providers with affiliated hospital of same name
     */
    public function scopeWithHospitals(Builder $query, string $label)
    {
        // return nothing on empty $label
        if (!$label) {
            return;
        }

        // try to find people within the same label name
        try {

            $hospital = Hospital::query()
                ->where('label', '=', $label)
                ->latest('version')
                ->firstOrFail();

            // find providers that have connection to $hospital
            $query->whereHas('hospitals', function (Builder $query) use ($hospital) {
                $query->where('hospital_id', $hospital->id);
            });

        // if hospital not found - add emptying where clause
        } catch (\Throwable $e) {
            $query->whereRaw('1 = 0');
        }
    }

    /**
     * Get Providers which match keywords
     */
    public function scopeWithKeywords(Builder $query, string $keywords, string $scope = null)
    {
        switch (strtoupper($scope)) {
            case self::SCOPE_CITY:
                $query->where(function (Builder $query) use ($keywords) {
                    $this->applyFilterCity($query, $keywords);
                });
                break;

            case self::SCOPE_SPECIALITY:
                $query->where(function (Builder $query) use ($keywords) {
                    $this->applyFilterSpeciality($query, $keywords);
                });
                break;

            case self::SCOPE_LANGUAGE:
                $query->where(function (Builder $query) use ($keywords) {
                    $this->applyFilterLanguage($query, $keywords);
                });
                break;

            default:
                $query->where(function (Builder $query) use ($keywords) {
                    $this
                        ->applyFilterProvider($query, $keywords)
                        ->applyFilterCity($query, $keywords)
                        ->applyFilterLocation($query, $keywords)
                        //->applyFilterSpeciality($query, $keywords)
                        //->applyFilterLanguage($query, $keywords)
                    ;
                });
                break;
        }
    }

    /**
     * Filter by latest available version
     */
    public function scopeWithVersion(Builder $query, $request)
    {
        // define latest version for current network
        $version = self::select('version as value')
            ->where('network_id', '=', $request->network_id)
            ->orderByDesc('version')
            ->first();

        // add query constrain
        $query->where('version', $version->value);
    }

    protected function applyFilterProvider(Builder $query, string $keywords): self
    {
        $query->where(function ($query) use ($keywords) {
            $query
                ->orWhere('label', 'like', "%$keywords%")
                ->orWhere('website', 'like', "%$keywords%");
        });

        return $this;
    }

    protected function applyFilterCity(Builder $query, string $keywords): self
    {
        /**
         * If the keyword is numeric, assume zip code
         * If keyword is a city then restrict on city name
         */
        if (is_numeric($keywords)) {
            $include = 'ZIP';

        } else {
            $cities = Location::getCities()
                ->map(fn($city) => strtolower($city))
                ->toArray();

            if (in_array(strtolower(trim($keywords)), $cities)) {
                $include = 'CITY';
            }
        }

        if (!empty($include)) {

            $query->orWhereHas('locations', function ($query) use ($include, $keywords) {
                if ($include === 'ZIP') {
                    $query
                        ->where('locations.address_zip', 'like', "%$keywords%");

                } elseif ($include === 'CITY') {
                    $query
                        ->where('locations.address_city', '=', $keywords);
                }
            });
        }

        return $this;
    }

    protected function applyFilterSpeciality(Builder $query, string $keywords): self
    {
        $query->orWhereHas('specialities', function ($query) use ($keywords) {
            $query->where('specialities.label', 'like', "%$keywords%");
        });

        return $this;
    }

    protected function applyFilterLanguage(Builder $query, string $keywords): self
    {
        $query->orWhereHas('languages', function ($query) use ($keywords) {
            $query->where('languages.label', 'like', "%$keywords%");
        });

        return $this;
    }

    protected function applyFilterLocation(Builder $query, string $keywords): self
    {
        $query->orWhereHas('locations', function ($query) use ($keywords) {
            $query->where('locations.label', 'like', "%$keywords%");
        });

        return $this;
    }

    /**
     * Gets Providers which belong to a particular Network
     */
    public function scopeWithNetwork(Builder $query, Network $network)
    {
        $query->where('network_id', '=', $network->id);
    }

    /**
     * Gets Providers which have a Location in a particular State
     */
    public function scopeWithState(Builder $query, State $state)
    {
        $query->whereHas('locations.state', function ($query) use ($state) {
            $query->where('address_state_id', '=', $state->id);
        });
    }

    public function scopeWithType(Builder $query, $type)
    {
        $query->where('is_facility', '=', $type === 'facility');
    }

    public static function findByVersionNpiAndNetworkOrFail(int $version, ?string $npi, Network $network)
    {
        return self::query()
            ->where('version', '=', $version)
            ->where('npi', '=', $npi)
            ->where('network_id', '=', $network->id)
            ->firstOrFail();
    }

    /**
     * Check for unique keys set
     */
    public function scopeUnique(Builder $query, $item)
    {
        $query
            ->where('version', $item->version)
            ->where('npi', $item->npi)
            ->where('network_id', $item->network_id);
    }

    /**
     * Find matching providers based on mode
     */
    public function scopeMatching(Builder $query, $provider, $mode = 'strict')
    {
        // exclude matching source
        $query->where('id', '!=', $provider->id);

        // add where clauses based on matching mode
        if ($mode == 'strict') {
            $query
                ->where('label', $provider->label)
                ->where('type', $provider->type)
                ->where('npi', $provider->npi)
                ->where('degree', $provider->degree)
                ->where('website', $provider->website)
                ->where('gender', $provider->gender)
                ->where('is_facility', $provider->is_facility)
                ->where('is_accepting_new_patients', $provider->is_accepting_new_patients);

        } elseif ($mode == 'loose') {
            $query
                ->where('label', $provider->label)
                ->where('npi', $provider->npi)
                ->where('is_facility', $provider->is_facility);

        } elseif ($mode == 'npi') {
            $query->where('npi', $provider->npi);
        }
    }

    public function existsForVersionAndNetwork(): bool
    {
        try {

            self::findByVersionNpiAndNetworkOrFail(
                $this->version,
                $this->npi,
                Network::findOrFail($this->network_id)
            );

            return true;

        } catch (\Throwable $e) {
            return false;
        }
    }
}
