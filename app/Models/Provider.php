<?php

namespace App\Models;

use App\ModelPivots\LocationProviderPivot;
use App\Traits\HasGetTableName;
use App\Traits\HasVersionScope;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 */
class Provider extends Model
{
    use HasFactory, Filterable, HasGetTableName, HasVersionScope;

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
        'npi',
        'label',
        'gender',
        'is_facility',
        'is_accepting_new_patients',
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
     * Get Providers which match keywords
     */
    public function scopeWithKeywords(Builder $query, string $keywords, string $scope = null)
    {
        switch (strtoupper($scope)) {
            case self::SCOPE_CITY:
                $this->applyFilterCity($query, $keywords);
                break;

            case self::SCOPE_SPECIALITY:
                $this->applyFilterSpeciality($query, $keywords);
                break;

            case self::SCOPE_LANGUAGE:
                $this->applyFilterLanguage($query, $keywords);
                break;

            default:
                $this
                    ->applyFilterProvider($query, $keywords)
                    ->applyFilterCity($query, $keywords)
                    ->applyFilterSpeciality($query, $keywords)
                    ->applyFilterLanguage($query, $keywords);
                break;
        }
    }

    protected function applyFilterProvider(Builder $query, string $keywords): self
    {
        $query
            ->orWhere('label', 'like', "%$keywords%")
            ->orWhere('website', 'like', "%$keywords%");

        return $this;
    }

    protected function applyFilterCity(Builder $query, string $keywords): self
    {
        $query->whereHas('locations', function ($query) use ($keywords) {
            $query->where('locations.address_city', 'like', "%$keywords%");
        });

        return $this;
    }

    protected function applyFilterSpeciality(Builder $query, string $keywords): self
    {
        $query->whereHas('specialities', function ($query) use ($keywords) {
            $query->where('specialities.label', 'like', "%$keywords%");
        });

        return $this;
    }

    protected function applyFilterLanguage(Builder $query, string $keywords): self
    {
        $query->whereHas('languages', function ($query) use ($keywords) {
            $query->where('languages.label', 'like', "%$keywords%");
        });

        return $this;
    }

    /**
     * Gets Providers which belong to a particular Network
     */
    public function scopeWithNetwork(Builder $query, Network $network)
    {
        $query->where('network_id', $network->id);
    }

    /**
     * Gets Providers which have a Location in a particular State
     */
    public function scopeWithState(Builder $query, State $state)
    {
        $query->whereHas('locations.state', function ($query) use ($state) {
            $query->where('address_state_id', $state->id);
        });
    }

    public function scopeWithType(Builder $query, $type)
    {
        $query->where('is_facility', $type === 'facility');
    }

    public static function findByNpiAndNetworkOrFail(string $npi, Network $network)
    {
        return Provider::query()
            ->where('npi', $npi)
            ->where('network_id', $network->id)
            ->firstOrFail();
    }
}
