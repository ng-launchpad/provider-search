<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    /**
     * Gets the Providers associated with the Language
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }
}
