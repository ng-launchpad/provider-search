<?php

namespace App\Models;

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
 */
class Speciality extends Model
{
    use HasFactory;
}
