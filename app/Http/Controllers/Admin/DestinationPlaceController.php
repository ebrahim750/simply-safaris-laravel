<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationPlaceRequest;
use App\Http\Requests\Admin\UpdateDestinationPlaceRequest;
use App\Models\Country;
use App\Models\Place;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DestinationPlaceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/destinations/places/Index', [
            'places' => Place::query()
                ->with('country:id,name')
                ->latest('id')
                ->get(['id', 'country_id', 'name', 'slug', 'created_at'])
                ->map(fn (Place $place): array => [
                    'id' => $place->id,
                    'country_id' => $place->country_id,
                    'country_name' => $place->country?->name ?? 'N/A',
                    'name' => $place->name,
                    'slug' => $place->slug,
                    'created_at' => $place->created_at?->toIso8601String(),
                    'created_at_display' => $place->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/destinations/places/Create', [
            'countries' => Country::query()
                ->orderBy('name')
                ->get(['id', 'name', 'flag_svg', 'flag_png'])
                ->map(fn (Country $country): array => [
                    'id' => $country->id,
                    'name' => $country->name,
                    'flag_svg' => $country->flag_svg,
                    'flag_png' => $country->flag_png,
                ]),
        ]);
    }

    public function store(StoreDestinationPlaceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Place::query()->create([
            'country_id' => (int) $validated['country_id'],
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'overview' => $validated['overview'] ?? null,
        ]);

        return to_route('admin.destinations.places')
            ->with('status', 'Place created.');
    }

    public function edit(Place $place): Response
    {
        return Inertia::render('admin/destinations/places/Edit', [
            'place' => [
                'id' => $place->id,
                'country_id' => $place->country_id,
                'name' => $place->name,
                'slug' => $place->slug,
                'overview' => $place->overview,
            ],
            'countries' => Country::query()
                ->orderBy('name')
                ->get(['id', 'name', 'flag_svg', 'flag_png'])
                ->map(fn (Country $country): array => [
                    'id' => $country->id,
                    'name' => $country->name,
                    'flag_svg' => $country->flag_svg,
                    'flag_png' => $country->flag_png,
                ]),
        ]);
    }

    public function update(UpdateDestinationPlaceRequest $request, Place $place): RedirectResponse
    {
        $validated = $request->validated();

        $place->update([
            'country_id' => (int) $validated['country_id'],
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name'], $place),
            'overview' => $validated['overview'] ?? null,
        ]);

        return to_route('admin.destinations.places')
            ->with('status', 'Place updated.');
    }

    public function destroy(Place $place): RedirectResponse
    {
        $place->delete();

        return to_route('admin.destinations.places')
            ->with('status', 'Place deleted.');
    }

    private function generateUniqueSlug(string $name, ?Place $ignorePlace = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'place';
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->slugExists($slug, $ignorePlace)) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?Place $ignorePlace = null): bool
    {
        return Place::query()
            ->when($ignorePlace, fn ($query) => $query->whereKeyNot($ignorePlace->id))
            ->where('slug', $slug)
            ->exists();
    }
}
