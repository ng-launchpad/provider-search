<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * App\Models\Setting
 *
 * @property int                             $id
 * @property string                          $key
 * @property mixed                           $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SettingFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereValue($value)
 * @mixin \Eloquent
 * @method static Builder|Setting withKey($key)
 */
class Setting extends Model
{
    use HasFactory;

    public static function set(string $key, $value): self
    {
        try {

            $existing        = self::getSetting($key);
            $existing->value = json_encode($value);
            $existing->save();

            return $existing;

        } catch (ModelNotFoundException $e) {

            $new = self::factory()
                ->make([
                    'key'   => $key,
                    'value' => json_encode($value),
                ]);

            $new->save();

            return $new;
        }
    }

    public static function get(string $key)
    {
        return json_decode(self::getSetting($key)->value);
    }

    public static function getSetting(string $key): self
    {
        return self::query()
            ->withKey($key)
            ->firstOrFail();
    }

    public static function unset($key): ?bool
    {
        return self::getSetting($key)->delete();
    }

    public function scopeWithKey(Builder $query, $key)
    {
        $query->where('key', '=', $key);
    }

    public static function version(): int
    {
        return self::get('version');
    }

    public static function nextVersion(): int
    {
        return self::version() + 1;
    }
}
