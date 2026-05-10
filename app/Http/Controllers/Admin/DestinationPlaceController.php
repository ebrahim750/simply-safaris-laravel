<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationPlaceRequest;
use App\Http\Requests\Admin\UpdateDestinationPlaceRequest;
use App\Models\Country;
use App\Models\Place;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
                ->get(['id', 'country_id', 'name', 'slug', 'main_image', 'created_at'])
                ->map(fn (Place $place): array => [
                    'id' => $place->id,
                    'country_id' => $place->country_id,
                    'country_name' => $place->country?->name ?? 'N/A',
                    'name' => $place->name,
                    'slug' => $place->slug,
                    'main_image' => $this->resolveImageUrl($place->main_image),
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

        $place = Place::query()->create([
            'country_id' => (int) $validated['country_id'],
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'overview' => $validated['overview'] ?? null,
        ]);

        if ($request->hasFile('main_image')) {
            $place->forceFill([
                'main_image' => $this->storeImage($request->file('main_image'), 'places/'.$place->id),
            ])->save();
        }

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
                'main_image' => $this->resolveImageUrl($place->main_image),
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

        if ($request->hasFile('main_image')) {
            if ($place->main_image) {
                Storage::disk('public')->delete($place->main_image);
            }

            $place->forceFill([
                'main_image' => $this->storeImage($request->file('main_image'), 'places/'.$place->id),
            ])->save();
        }

        return to_route('admin.destinations.places')
            ->with('status', 'Place updated.');
    }

    public function destroy(Place $place): RedirectResponse
    {
        if ($place->main_image) {
            Storage::disk('public')->delete($place->main_image);
        }

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

    private function storeImage(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
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
