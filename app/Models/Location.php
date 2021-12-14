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

    /**
     * Gets the Providers associated with the Location
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }

    /**
     * Generates a hash representing this Location
     *
     * @return string
     */
    public function hash()
    {
        return md5(json_encode([
            'label'            => $this->label,
            'type'             => $this->type,
            'address_line_1'   => $this->address_line_1,
            'address_city'     => $this->address_city,
            'address_county'   => $this->address_county,
            'address_state_id' => $this->address_state_id,
            'address_zip'      => $this->address_zip,
            'phone'            => $this->phone,
        ]));
    }

    public static function saving($callback)
    {
    }
}
