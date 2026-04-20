<?php

namespace App\Models;

use Database\Factories\TourFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable(['name', 'price', 'main_image', 'gallery_images'])]
class Tour extends Model
{
    /** @use HasFactory<TourFactory> */
    use HasFactory;

    public function itineraryItems(): HasMany
    {
        return $this->hasMany(TourItineraryItem::class)->orderBy('position');
    }

    public function inclusions(): HasMany
    {
        return $this->hasMany(TourInclusion::class)->orderBy('position');
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(TourExclusion::class)->orderBy('position');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(TourCategory::class, 'tour_category_pivot')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(TourActivity::class, 'tour_activity_pivot')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function destinations(): MorphToMany
    {
        return $this->morphToMany(Destination::class, 'destinationable')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'gallery_images' => 'array',
        ];
    }
}
