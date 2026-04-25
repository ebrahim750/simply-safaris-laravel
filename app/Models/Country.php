<?php

namespace App\Models;

use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'country_code',
    'flag_svg',
    'flag_png',
    'overview',
    'rates',
    'best_time_to_go',
    'high_season',
    'time_difference',
    'language',
    'electrical',
    'currency',
    'driving_side',
    'tourist_info',
])]
class Country extends Model
{
    /** @use HasFactory<CountryFactory> */
    use HasFactory;

    public function places(): HasMany
    {
        return $this->hasMany(Place::class)->orderBy('name');
    }
}
