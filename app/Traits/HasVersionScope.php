<?php

namespace App\Traits;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;

trait HasVersionScope
{
    public function scopeWithVersion(Builder $query)
    {
        $query->where('version', Setting::version());
    }
}
