<?php

namespace App\ModelFilters;

use App\Models\Network;
use App\Models\State;
use EloquentFilter\ModelFilter;

class ProviderFilter extends ModelFilter
{
    /**
     * Filter Providers by ?keywords attribute
     */
    public function keywords($keywords)
    {
        $this->withKeywords($keywords, request()->query('scope'));
    }

    public function network($id)
    {
        $this->withNetwork(
            Network::findOrFail($id)
        );
    }

    public function state($id)
    {
        $this->withState(
            State::findOrFail($id)
        );
    }

    public function type($type)
    {
        $this->withType($type);
    }
}
