<?php

namespace App\ModelPivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LocationProviderPivot extends Pivot
{
    protected $casts = [
        'is_primary' => 'boolean',
    ];
}
