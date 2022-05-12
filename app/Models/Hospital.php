<?php

namespace App\Models;

use App\Models\Concerns\HasGetTableName;
use App\Models\Concerns\HasVersionScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hospital
 *
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Provider[] $providers
 * @property-read int|null $providers_count
 * @method static \Database\Factories\HospitalFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital query()
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital withVersion()
 * @property int $version
 * @method static \Illuminate\Database\Eloquent\Builder|Hospital whereVersion($value)
 */
class Hospital extends Model
{
    use HasFactory, HasGetTableName, HasVersionScope;

    protected $fillable = [
        'version',
        'label',
    ];

    /**
     * Gets the Providers associated with the Hospital
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }

    /**
     * Find matching Hospital
     */
    public function scopeMatching(Builder $query, $item)
    {
        $query->where('label', $item->label);
    }

    /**
     * Check if current Hospital exists for current version
     */
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
