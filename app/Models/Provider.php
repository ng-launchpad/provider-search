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
 */
class Provider extends Model
{
    use HasFactory, Filterable;

    /**
     * Gets the state associated with the provider
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get Providers which have a State
     */
    public function scopeWithState(Builder $query, State $state)
    {
        $query->whereHas('state', function ($query) use ($state) {
            $query->where('state_id', $state->id);
        });
    }

    /**
     * Get providers which match keywords
     */
    public function scopeWithKeywords(Builder $query, string $keywords)
    {
        $query->where('label', 'like' , "%$keywords%");
    }
}
