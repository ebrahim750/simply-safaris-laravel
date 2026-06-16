<?php

namespace App\Models;

use Database\Factories\TourInclusionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tour_id', 'position', 'text'])]
class TourInclusion extends Model
{
    /** @use HasFactory<TourInclusionFactory> */
    use HasFactory;

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
