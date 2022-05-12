<?php

namespace App\Models;

use App\Models\Concerns\HasGetTableName;
use App\Models\Concerns\HasVersionScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language
 *
 * @property int                                                                  $id
 * @property string                                                               $label
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @method static \Database\Factories\LanguageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Provider[] $providers
 * @property-read int|null                                                        $providers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Language withVersion()
 * @property int                                                                  $version
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereVersion($value)
 */
class Language extends Model
{
    use HasFactory, HasGetTableName, HasVersionScope;

    protected $fillable = [
        'version',
        'label',
    ];

    /**
     * Gets the Providers associated with the Language
     */
    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }

    /**
     * Find matching Language
     */
    public function scopeMatching(Builder $query, $item)
    {
        $query->where('label', $item->label);
    }

    /**
     * Find Language by label and version
     */
    public static function findByVersionAndLabelOrFail(int $version, string $label): self
    {
        return Language::query()
            ->where('version', '=', $version)
            ->where('label', '=', $label)
            ->firstOrFail();
    }

    /**
     * Check if current Language exists for current version
     */
    public function existsForVersion(): bool
    {
        try {

            self::findByVersionAndLabelOrFail($this->version, $this->label);
            return true;

        } catch (\Throwable $e) {
            return false;
        }
    }
}
