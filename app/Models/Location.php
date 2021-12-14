<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * Gets the State associated with the Location
     */
    public function addressState()
    {
        return $this->belongsTo(State::class, 'address_state_id');
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }
}
