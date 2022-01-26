<?php

namespace App\Models;

use App\Traits\HasGetTableName;
use App\Traits\HasVersionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $label
 * @property string $type
 * @property string $address_line_1
 * @property string $address_city
 * @property string|null $address_county
 * @property int $address_state_id
 * @property string $address_zip
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\State $addressState
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Provider[] $providers
 * @property-read int|null $providers_count
 * @method static \Database\Factories\LocationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddressCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddressCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddressStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddressZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $hash
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereHash($value)
 * @property-read \App\Models\State $state
 */
class Location extends Model
{
    use HasFactory, HasGetTableName, HasVersionScope;

    /**
     * Gets the State associated with the Location
     */
    public function state()
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
