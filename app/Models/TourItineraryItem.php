<?php

namespace App\Models;

use Database\Factories\TourItineraryItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tour_id', 'position', 'title', 'content'])]
class TourItineraryItem extends Model
{
    /** @use HasFactory<TourItineraryItemFactory> */
    use HasFactory;

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
