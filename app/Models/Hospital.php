<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    /**
     * Gets the Providers associated with the Hospital
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }
}
