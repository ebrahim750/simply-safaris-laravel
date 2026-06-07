<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Place;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DestinationController extends Controller
{
    public function index(): Response
    {
        $countries = Country::query()
            ->with([
                'places' => fn ($query) => $query
                    ->select('id', 'country_id', 'name', 'slug', 'main_image')
                    ->withCount('tours')
                    ->orderBy('name'),
            ])
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'country_code', 'main_image', 'overview'])
            ->map(fn (Country $country) => [
                'id' => $country->id,
                'name' => $country->name,
                'slug' => $country->slug,
                'country_code' => $country->country_code,
                'main_image' => $this->resolveImageUrl($country->main_image),
                'overview' => $country->overview,
                'places' => $country->places->map(fn (Place $place) => [
                    'id' => $place->id,
                    'name' => $place->name,
                    'slug' => $place->slug,
                    'main_image' => $this->resolveImageUrl($place->main_image),
                    'tours_count' => $place->tours_count,
                ])->all(),
            ]);

        return Inertia::render('destinations/Index', [
            'countries' => $countries,
        ]);
    }

    public function showCountry(Country $country): Response
    {
        $country->main_image = $this->resolveImageUrl($country->main_image);

        $country->load([
            'places' => fn ($query) => $query
                ->orderBy('name')
                ->with([
                    'tours' => fn ($query) => $query
                        ->select('tours.id', 'name', 'main_image', 'price', 'duration_days')
                        ->orderByPivot('position'),
                ])
                ->withCount('tours'),
        ]);

        $country->places->each(function (Place $place) {
            $place->main_image = $this->resolveImageUrl($place->main_image);
            $place->setRelation('tours', $place->tours->take(4));
            $place->tours->each(function ($tour) {
                $tour->main_image = $this->resolveImageUrl($tour->main_image);
            });
        });

        return Inertia::render('destinations/Country', [
            'country' => $country,
        ]);
    }


    private function resolveImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://', '/'])
            ? $path
            : Storage::disk('public')->url($path);
    }
}

