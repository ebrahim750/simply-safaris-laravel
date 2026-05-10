<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationCountryRequest;
use App\Http\Requests\Admin\UpdateDestinationCountryRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class DestinationCountryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/destinations/countries/Countries', [
            'countries' => Country::query()
                ->latest('id')
                ->get(['id', 'name', 'slug', 'country_code', 'created_at'])
                ->map(fn (Country $country): array => [
                    'country_code' => $country->country_code,
                    'id' => $country->id,
                    'name' => $country->name,
                    'slug' => $country->slug,
                    'created_at' => $country->created_at?->toIso8601String(),
                    'created_at_display' => $country->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/destinations/countries/Create', [
            'country_options' => $this->getCountryCatalog(),
        ]);
    }

    public function store(StoreDestinationCountryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $countryOption = $this->findCountryOptionByName($validated['name']);

        $country = Country::query()->create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'country_code' => $countryOption['code'] ?? null,
            'overview' => $validated['overview'] ?? null,
            'best_time_to_go' => $validated['best_time_to_go'] ?? null,
            'high_season' => $validated['high_season'] ?? null,
            'electrical' => $validated['electrical'] ?? null,
            'tourist_info' => $validated['tourist_info'] ?? null,
        ]);

        if ($request->hasFile('main_image')) {
            $country->forceFill([
                'main_image' => $this->storeImage($request->file('main_image'), 'countries/'.$country->id),
            ])->save();
        }

        return to_route('admin.destinations.countries')
            ->with('status', 'Country created.');
    }

    public function edit(Country $country): Response
    {
        return Inertia::render('admin/destinations/countries/Edit', [
            'country' => [
                'id' => $country->id,
                'name' => $country->name,
                'slug' => $country->slug,
                'overview' => $country->overview,
                'main_image' => $this->resolveImageUrl($country->main_image),
                'best_time_to_go' => $country->best_time_to_go,
                'high_season' => $country->high_season,
                'electrical' => $country->electrical,
                'tourist_info' => $country->tourist_info,
            ],
        ]);
    }

    public function update(UpdateDestinationCountryRequest $request, Country $country): RedirectResponse
    {
        $validated = $request->validated();
        $countryOption = $this->findCountryOptionByName($validated['name']);

        $country->update([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name'], $country),
            'country_code' => $countryOption['code'] ?? $country->country_code,
            'overview' => $validated['overview'] ?? null,
            'best_time_to_go' => $validated['best_time_to_go'] ?? null,
            'high_season' => $validated['high_season'] ?? null,
            'electrical' => $validated['electrical'] ?? null,
            'tourist_info' => $validated['tourist_info'] ?? null,
        ]);

        if ($request->hasFile('main_image')) {
            if ($country->main_image) {
                Storage::disk('public')->delete($country->main_image);
            }

            $country->forceFill([
                'main_image' => $this->storeImage($request->file('main_image'), 'countries/'.$country->id),
            ])->save();
        }

        return to_route('admin.destinations.countries')
            ->with('status', 'Country updated.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        if ($country->main_image) {
            Storage::disk('public')->delete($country->main_image);
        }

        $country->delete();

        return to_route('admin.destinations.countries')
            ->with('status', 'Country deleted.');
    }

    private function generateUniqueSlug(string $name, ?Country $ignoreCountry = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'country';
        $slug = $baseSlug;
        $suffix = 2;

        while ($this->slugExists($slug, $ignoreCountry)) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?Country $ignoreCountry = null): bool
    {
        return Country::query()
            ->when($ignoreCountry, fn ($query) => $query->whereKeyNot($ignoreCountry->id))
            ->where('slug', $slug)
            ->exists();
    }

    /**
     * @return Collection<int, array{name: string, official_name: string|null, code: string, flag_svg: string|null, flag_png: string|null}>
     */
    private function getCountryCatalog(): Collection
    {
        $cacheKey = 'countries.catalog.v1';
        $cachedValue = Cache::get($cacheKey);

        if ($cachedValue instanceof Collection) {
            return $cachedValue;
        }

        if (is_array($cachedValue)) {
            return collect($cachedValue);
        }

        if ($cachedValue !== null) {
            Cache::forget($cacheKey);
        }

        try {
            $response = Http::connectTimeout(3)
                ->timeout(6)
                ->get('https://restcountries.com/v3.1/all?fields=name,cca2,flags');

            if (! $response->successful()) {
                return collect();
            }

            $countryCatalog = $response->collect()
                ->map(fn (mixed $item): array => [
                    'name' => trim((string) data_get($item, 'name.common')),
                    'official_name' => filled(data_get($item, 'name.official'))
                        ? trim((string) data_get($item, 'name.official'))
                        : null,
                    'code' => Str::lower((string) data_get($item, 'cca2')),
                    'flag_svg' => filled(data_get($item, 'flags.svg'))
                        ? (string) data_get($item, 'flags.svg')
                        : null,
                    'flag_png' => filled(data_get($item, 'flags.png'))
                        ? (string) data_get($item, 'flags.png')
                        : null,
                ])
                ->filter(fn (array $item): bool => filled($item['name']) && filled($item['code']))
                ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
                ->values()
                ->all();

            Cache::put($cacheKey, $countryCatalog, now()->addDay());

            return collect($countryCatalog);
        } catch (Throwable) {
            return collect();
        }
    }

    /**
     * @return array{name: string, official_name: string|null, code: string, flag_svg: string|null, flag_png: string|null}|null
     */
    private function findCountryOptionByName(string $name): ?array
    {
        return $this->getCountryCatalog()
            ->first(fn (array $country): bool => Str::lower($country['name']) === Str::lower($name));
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
