<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Network
 *
 * @property int $id
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Provider[] $providers
 * @property-read int|null $providers_count
 * @method static \Database\Factories\NetworkFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Network newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Network newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Network query()
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $search_label
 * @property string $search_sublabel
 * @property string $network_label
 * @property string $browse_label
 * @property string $config_key
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereBrowseLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereConfigKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereNetworkLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereSearchLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereSearchSublabel($value)
 * @property string|null $legal
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereLegalBrowse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereLegalFacility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereLegalHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereLegalProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereLegalSearch($value)
 * @property string|null $legal_home
 * @property string|null $legal_search
 * @property string|null $legal_browse
 * @property string|null $legal_provider
 * @property string|null $legal_facility
 */
class Network extends Model
{
    use HasFactory;

    /**
     * Gets all Providers associated with the Network
     */
    public function providers()
    {
        return $this->hasMany(Provider::class);
    }

    public function getConfig(): array
    {
        return config($this->config_key);
    }

    public static function getByLabelOrFail(string $label)
    {
        return static::where('label', '=', $label)->firstOrFail();
    }

    public function isEnabled(): bool
    {
        return $this->getConfig()['enabled'];
    }
}
