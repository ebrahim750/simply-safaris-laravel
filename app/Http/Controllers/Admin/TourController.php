<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('admin/tours/Tours', [
            'tours' => Tour::query()
                ->latest('id')
                ->get(['id', 'name', 'price', 'is_hidden', 'is_featured_on_home', 'is_featured_as_seasonal_recommendation', 'created_at'])
                ->map(fn (Tour $tour): array => [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'price' => $tour->price,
                    'is_hidden' => $tour->is_hidden,
                    'is_featured_on_home' => $tour->is_featured_on_home,
                    'is_featured_as_seasonal_recommendation' => $tour->is_featured_as_seasonal_recommendation,
                    'created_at' => $tour->created_at?->toIso8601String(),
                    'created_at_display' => $tour->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }
}
