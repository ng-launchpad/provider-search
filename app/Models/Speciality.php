<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    use HasFactory;

    /**
     * Gets the Providers associated with the Speciality
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }
}
