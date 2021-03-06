<?php

namespace App\Models;

use App\Models\Concerns\HasGetTableName;
use App\Models\Concerns\HasVersionScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Location
 *
 * @property int                                                                  $id
 * @property string                                                               $label
 * @property string                                                               $type
 * @property string                                                               $address_line_1
 * @property string                                                               $address_city
 * @property string|null                                                          $address_county
 * @property int                                                                  $address_state_id
 * @property string                                                               $address_zip
 * @property string|null                                                          $phone
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @property-read \App\Models\State                                               $addressState
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Provider[] $providers
 * @property-read int|null                                                        $providers_count
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
 * @property string|null                                                          $hash
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereHash($value)
 * @property-read \App\Models\State                                               $state
 * @method static \Illuminate\Database\Eloquent\Builder|Location withVersion()
 * @property int                                                                  $version
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereVersion($value)
 * @property string|null                                                          $address_line_2
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAddressLine2($value)
 * @method static Builder|Location matching($item)
 */
class Location extends Model
{
    use HasFactory, HasGetTableName, HasVersionScope;

    protected $fillable = [
        'label',
        'version',
        'type',
        'address_line_1',
        'address_line_2',
        'address_city',
        'address_county',
        'address_state_id',
        'address_zip',
        'phone',
    ];

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
     * Find matching Location
     */
    public function scopeMatching(Builder $query, $item)
    {
        $query
            ->where('label', $item->label)
            ->where('type', $item->type)
            ->where('address_line_1', $item->address_line_1)
            ->where('address_city', $item->address_city)
            ->where('address_county', $item->address_county)
            ->where('address_state_id', $item->address_state_id)
            ->where('address_zip', $item->address_zip)
            ->where('phone', $item->phone)
            ;
    }

    /**
     * Generates a hash representing this Location
     *
     * @return string
     */
    public function generateHash(): string
    {
        return self::buildHashFromData($this);
    }

    public static function buildHashFromData(object $data): string
    {
        return md5(json_encode([
            'label'            => $data->label,
            'type'             => $data->type,
            'address_line_1'   => $data->address_line_1,
            'address_city'     => $data->address_city,
            'address_county'   => $data->address_county,
            'address_state_id' => $data->address_state_id,
            'address_zip'      => $data->address_zip,
            'phone'            => $data->phone,
        ]));
    }

    public function existsForVersion(): bool
    {
        try {

            self::query()
                ->where('version', '=', $this->version)
                ->where('hash', '=', $this->hash)
                ->firstOrFail();

            return true;

        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function getCities(): \Illuminate\Support\Collection
    {
        $result = DB::query()
            ->distinct('address_city')
            ->from(self::getTableName())
            ->get(['address_city']);

        return $result->map(fn(\stdClass $location) => $location->address_city);
    }
}
