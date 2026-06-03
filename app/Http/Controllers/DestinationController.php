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
            ->with(['places' => fn ($query) => $query->select('id', 'country_id', 'name', 'slug')->orderBy('name')])
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'main_image', 'overview'])
            ->map(fn (Country $country) => [
                'id' => $country->id,
                'name' => $country->name,
                'slug' => $country->slug,
                'main_image' => $this->resolveImageUrl($country->main_image),
                'overview' => $country->overview,
                'places' => $country->places,
            ]);

        return Inertia::render('destinations/Index', [
            'countries' => $countries,
        ]);
    }

    public function showCountry(Country $country): Response
    {
        $country->main_image = $this->resolveImageUrl($country->main_image);

        return Inertia::render('destinations/Country', [
            'country' => $country->load(['places' => fn ($query) => $query->orderBy('name')]),
        ]);
    }

    public function showPlace(Country $country, Place $place): Response
    {
        $country->main_image = $this->resolveImageUrl($country->main_image);
        $place->main_image = $this->resolveImageUrl($place->main_image);

        return Inertia::render('destinations/Place', [
            'country' => $country,
            'place' => $place->load('country'),
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
