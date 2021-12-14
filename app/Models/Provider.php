<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Provider
 *
 * @property int                             $id
 * @property string                          $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @property int $state_id
 * @property-read \App\Models\State $state
 * @method static Builder|Provider whereStateId($value)
 * @method static Builder|Provider withKeywords(string $keywords)
 * @method static Builder|Provider withState(\App\Models\State $state)
 * @property string $npi
 * @property string|null $phone
 * @property string|null $degree
 * @property string|null $website
 * @property string|null $gender
 * @property int|null $network_id
 * @property int $is_facility
 * @property int $is_accepting_new_patients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null $locations_count
 * @property-read \App\Models\Network|null $network
 * @method static Builder|Provider whereDegree($value)
 * @method static Builder|Provider whereGender($value)
 * @method static Builder|Provider whereIsAcceptingNewPatients($value)
 * @method static Builder|Provider whereIsFacility($value)
 * @method static Builder|Provider whereNetworkId($value)
 * @method static Builder|Provider whereNpi($value)
 * @method static Builder|Provider wherePhone($value)
 * @method static Builder|Provider whereWebsite($value)
 */
class Provider extends Model
{
    use HasFactory, Filterable;

    const GENDER_MALE   = 'MALE';
    const GENDER_FEMALE = 'FEMALE';

    /**
     * Gets the Network associated with the Provider
     */
    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    /**
     * Get Providers which match keywords
     */
    public function scopeWithKeywords(Builder $query, string $keywords)
    {
        $query->where('label', 'like', "%$keywords%");
    }

    public function scopeWithState(Builder $query, State $state)
    {
        $query->whereHas('locations.addressState', function ($query) use ($state) {
            $query->where('address_state_id', $state->id);
        });
    }
}
