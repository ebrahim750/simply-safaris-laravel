<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(Request $request): Response
    {
        $currency = SiteSetting::query()->value('currency') ?: 'USD';

        return Inertia::render('admin/tours/Tours', [
            'tours' => Tour::query()
                ->latest('id')
                ->get(['id', 'name', 'main_image', 'price', 'is_hidden', 'is_featured_on_home', 'is_featured_as_seasonal_recommendation', 'created_at'])
                ->map(fn (Tour $tour): array => [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'main_image' => blank($tour->main_image)
                        ? null
                        : (Str::startsWith($tour->main_image, ['http://', 'https://', '/'])
                            ? $tour->main_image
                            : Storage::disk('public')->url($tour->main_image)),
                    'price_amount' => (float) $tour->price,
                    'price' => Number::currency($tour->price, in: $currency, precision: 0),
                    'is_hidden' => $tour->is_hidden,
                    'is_featured_on_home' => $tour->is_featured_on_home,
                    'is_featured_as_seasonal_recommendation' => $tour->is_featured_as_seasonal_recommendation,
                    'created_at' => $tour->created_at?->toIso8601String(),
                    'created_at_display' => $tour->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }

    public function toggleHidden(Tour $tour): RedirectResponse
    {
        $tour->forceFill([
            'is_hidden' => ! $tour->is_hidden,
        ])->save();

        return back()->with(
            'status',
            $tour->is_hidden ? 'Tour hidden.' : 'Tour unhidden.',
        );
    }
}
