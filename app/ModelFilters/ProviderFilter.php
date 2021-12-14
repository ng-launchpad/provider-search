<?php

namespace App\ModelFilters;

use App\Models\State;
use EloquentFilter\ModelFilter;

class ProviderFilter extends ModelFilter
{
    /**
     * Filter Providers by ?keywords attribute
     */
    public function keywords($keywords)
    {
        $this->withKeywords($keywords);
    }

    public function state($id)
    {
        $this->withState(
            State::findOrFail($id)
        );
    }
}
