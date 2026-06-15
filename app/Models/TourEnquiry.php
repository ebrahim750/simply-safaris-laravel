<?php

namespace App\Models;

use Database\Factories\TourEnquiryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourEnquiry extends Model
{
    /** @use HasFactory<TourEnquiryFactory> */
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'name',
        'email',
        'phone',
        'booking_date',
        'adults',
        'children',
        'message',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}
