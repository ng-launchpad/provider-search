<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasVersionScope
{
    public function scopeWithVersion(Builder $query)
    {
        $query->where('version', $this->getVersion());
    }

    protected function getVersion(): int
    {
        //  @todo (Pablo 2022-01-26) - define logic for getting version
        return 1;
    }
}
