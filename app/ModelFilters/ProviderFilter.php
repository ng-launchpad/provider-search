<?php

namespace App\ModelFilters;

use App\Models\State;
use EloquentFilter\ModelFilter;

class ProviderFilter extends ModelFilter
{
    /**
     * Filter Providers by ?state attribute
     */
    public function state($id)
    {
        $state = State::findOrFail($id);
        $this->withState($state);
    }
}
