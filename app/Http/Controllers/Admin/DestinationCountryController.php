<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationCountryRequest;
use App\Http\Requests\Admin\UpdateDestinationCountryRequest;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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
                ->get(['id', 'name', 'slug', 'country_code', 'flag_svg', 'flag_png', 'created_at'])
                ->map(fn (Country $country): array => [
                    'country_code' => $country->country_code,
                    'flag_svg' => $country->flag_svg,
                    'flag_png' => $country->flag_png,
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

        Country::query()->create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'country_code' => $countryOption['code'] ?? null,
            'flag_svg' => $countryOption['flag_svg'] ?? null,
            'flag_png' => $countryOption['flag_png'] ?? null,
        ]);

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
            'flag_svg' => $countryOption['flag_svg'] ?? $country->flag_svg,
            'flag_png' => $countryOption['flag_png'] ?? $country->flag_png,
        ]);

        return to_route('admin.destinations.countries')
            ->with('status', 'Country updated.');
    }

    public function destroy(Country $country): RedirectResponse
    {
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
}
