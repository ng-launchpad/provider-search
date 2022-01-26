<?php

namespace App\ModelFilters;

use App\Models\Network;
use App\Models\State;
use EloquentFilter\ModelFilter;

class SettingFilter extends ModelFilter
{
    /**
     * Filter Settings by key attribute
     */
    public function key($key)
    {
        $this->withKey($key);
    }
}
