<?php

namespace App\ModelPivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\ModelPivots\LocationProviderPivot
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LocationProviderPivot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationProviderPivot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationProviderPivot query()
 * @mixin \Eloquent
 */
class LocationProviderPivot extends Pivot
{
    protected $casts = [
        'is_primary' => 'boolean',
    ];
}
