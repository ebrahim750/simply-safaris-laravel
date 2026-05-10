<?php

namespace App\Models;

use Database\Factories\TourCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'description', 'image'])]
class TourCategory extends Model
{
    /** @use HasFactory<TourCategoryFactory> */
    use HasFactory;

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_category_pivot')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }
}
