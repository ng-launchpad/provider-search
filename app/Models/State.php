<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * App\Models\State
 *
 * @property int                                                                  $id
 * @property string                                                               $label
 * @property string                                                               $code
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @method static \Database\Factories\StateFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Provider[] $providers
 * @property-read int|null                                                        $providers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Location[] $locations
 * @property-read int|null                                                        $locations_count
 */
class State extends Model
{
    use HasFactory;

    public static function findByCodeOrFail(string $value)
    {
        try {
            return self::where('code', $value)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException(
                sprintf(
                    '%s Searched for value "%s"',
                    $e->getMessage(),
                    $value
                ),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Gets all Locations associated with the state
     */
    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
