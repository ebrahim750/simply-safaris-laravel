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
    'overview',
    'main_image',
    'best_time_to_go',
    'high_season',
    'electrical',
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
