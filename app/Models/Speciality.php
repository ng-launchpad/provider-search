<?php

namespace App\Models;

use App\Models\Concerns\HasGetTableName;
use App\Models\Concerns\HasVersionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Speciality
 *
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SpecialityFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality query()
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Provider[] $providers
 * @property-read int|null $providers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality withVersion()
 * @property int $version
 * @method static \Illuminate\Database\Eloquent\Builder|Speciality whereVersion($value)
 */
class Speciality extends Model
{
    use HasFactory, HasGetTableName, HasVersionScope;

    protected $fillable = [
        'version',
        'label',
    ];

    /**
     * Gets the Providers associated with the Speciality
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }

    public function existsForVersion(): bool
    {
        try {

            self::query()
                ->where('version', '=', $this->version)
                ->where('label', '=', $this->label)
                ->firstOrFail();

            return true;

        } catch (\Throwable $e) {
            return false;
        }
    }
}
