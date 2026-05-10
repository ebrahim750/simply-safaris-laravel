<?php

namespace App\Models;

use Database\Factories\TourActivityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'description', 'image'])]
class TourActivity extends Model
{
    /** @use HasFactory<TourActivityFactory> */
    use HasFactory;

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_activity_pivot')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }
}
