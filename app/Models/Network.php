<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;

    /**
     * Gets all Providers associated with the Network
     */
    public function providers()
    {
        return $this->hasMany(Provider::class);
    }
}
