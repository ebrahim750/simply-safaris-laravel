<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Tour;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(): Response
    {
        $tours = Tour::query()
            ->where('is_hidden', false)
            ->latest('id')
            ->with(['categories:id,name', 'places:id,name,country_id', 'places.country:id,name'])
            ->paginate(9)
            ->through(fn (Tour $tour): array => $this->mapTour($tour));

        $countries = Country::query()
            ->with(['places' => fn ($query) => $query->select('id', 'country_id', 'name')->orderBy('name')])
            ->orderBy('name')
            ->get(['id', 'name', 'country_code'])
            ->map(fn ($country) => [
                'id' => $country->id,
                'name' => $country->name,
                'country_code' => $country->country_code,
                'places' => $country->places->map(fn ($place) => ['id' => $place->id, 'name' => $place->name])->all(),
            ]);

        return Inertia::render('tours/Index', [
            'tours' => $tours,
            'countries' => $countries,
        ]);
    }

    public function show(Tour $tour): Response
    {
        $tour->load(['categories:id,name', 'activities:id,name', 'places:id,name', 'itineraryItems', 'inclusions', 'exclusions']);

        return Inertia::render('tours/Show', [
            'tour' => [
                'id' => $tour->id,
                'name' => $tour->name,
                'main_image' => $this->resolveImageUrl($tour->main_image),
                'gallery_images' => collect($tour->gallery_images ?? [])
                    ->map(fn (string $path): string => $this->resolveImageUrl($path))
                    ->values()
                    ->all(),
                'price' => Number::currency($tour->price, precision: 0),
                'price_amount' => (float) $tour->price,
                'duration_days' => $tour->duration_days,
                'overview' => $tour->overview,
                'pricing_detailed' => $tour->pricing_detailed,
                'external_url' => $tour->external_url,
                'external_link_button_text' => $tour->external_link_button_text,
                'show_external_booking' => $tour->show_external_booking,
                'categories' => $tour->categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->all(),
                'activities' => $tour->activities->map(fn ($a) => ['id' => $a->id, 'name' => $a->name])->all(),
                'places' => $tour->places->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'country' => $p->country?->name])->all(),
                'itinerary_items' => $tour->itineraryItems->map(fn ($i) => ['title' => $i->title, 'content' => $i->content])->all(),
                'inclusions' => $tour->inclusions->map(fn ($i) => ['text' => $i->text])->all(),
                'exclusions' => $tour->exclusions->map(fn ($e) => ['text' => $e->text])->all(),
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTour(Tour $tour): array
    {
        return [
            'id' => $tour->id,
            'name' => $tour->name,
            'main_image' => $this->resolveImageUrl($tour->main_image),
            'price' => Number::currency($tour->price, precision: 0),
            'price_amount' => (float) $tour->price,
            'duration_days' => $tour->duration_days,
            'categories' => $tour->categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->all(),
            'places' => $tour->places->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'country' => $p->country?->name])->all(),
        ];
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::contains($path, ['via.placeholder.com', 'placehold.co'])) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://', '/'])
            ? $path
            : Storage::disk('public')->url($path);
    }
}
