<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Place;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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

        $allTourPrices = $country->places->pluck('tours')->flatten()->pluck('price')->filter();

        $ratesRange = null;
        if ($allTourPrices->isNotEmpty()) {
            $minPrice = (int) $allTourPrices->min();
            $maxPrice = (int) $allTourPrices->max();
            $ratesRange = '$'.$minPrice.' to $'.$maxPrice.' pp/day';
        }

        $country->timezone = $this->resolveTimezones($country->country_code);

        $countryData = $this->resolveCountryData($country->country_code);
        $country->languages = $countryData['languages'];
        $country->currency = $countryData['currency'];
        $country->driving_side = $countryData['driving_side'];
        $country->electrical = $this->resolveElectrical($country->country_code);
        $country->climate = $this->resolveClimate($country->country_code, $countryData['lat'], $countryData['lng']);

        $country->places->each(function (Place $place) {
            $place->main_image = $this->resolveImageUrl($place->main_image);
            $place->setRelation('tours', $place->tours->take(4));
            $place->tours->each(function ($tour) {
                $tour->main_image = $this->resolveImageUrl($tour->main_image);
            });
        });

        return Inertia::render('destinations/Country', [
            'country' => $country,
            'rates_range' => $ratesRange,
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

    private function resolveTimezones(?string $countryCode): ?string
    {
        if (blank($countryCode)) {
            return null;
        }

        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, strtoupper($countryCode));

        if ($timezones === []) {
            return null;
        }

        $offsets = [];
        foreach ($timezones as $tz) {
            $now = new \DateTime('now', new \DateTimeZone($tz));
            $offset = $now->format('P'); // e.g. "+03:00"
            $sign = $offset[0];
            $hours = (int) substr($offset, 1, 2);
            $minutes = (int) substr($offset, 4, 2);

            if ($minutes === 0) {
                $offsets[] = 'UTC'.$sign.$hours;
            } else {
                $offsets[] = 'UTC'.$sign.$hours.':'.str_pad((string) $minutes, 2, '0', STR_PAD_LEFT);
            }
        }

        $offsets = array_unique($offsets);
        sort($offsets);

        return implode(', ', $offsets);
    }

    /**
     * @return array{languages: string|null, currency: string|null, driving_side: string|null, lat: float|null, lng: float|null}
     */
    private function resolveCountryData(?string $countryCode): array
    {
        if (blank($countryCode)) {
            return ['languages' => null, 'currency' => null, 'driving_side' => null, 'lat' => null, 'lng' => null];
        }

        try {
            $response = Http::connectTimeout(3)
                ->timeout(6)
                ->get('https://restcountries.com/v3.1/alpha/'.strtolower($countryCode).'?fields=languages,currencies,car,latlng');

            if (! $response->successful()) {
                return ['languages' => null, 'currency' => null, 'driving_side' => null, 'lat' => null, 'lng' => null];
            }

            $data = $response->json();

            $languages = null;
            if (filled($data['languages'] ?? null)) {
                $languages = implode(', ', array_values($data['languages']));
            }

            $currency = null;
            if (filled($data['currencies'] ?? null)) {
                $parts = [];
                foreach ($data['currencies'] as $code => $info) {
                    $name = is_array($info) ? ($info['name'] ?? $code) : $code;
                    $parts[] = "{$name} ({$code})";
                }
                $currency = implode(', ', $parts);
            }

            $drivingSide = null;
            if (filled($data['car']['side'] ?? null)) {
                $drivingSide = 'On the '.ucfirst((string) $data['car']['side']);
            }

            $lat = is_array($data['latlng'] ?? null) && count($data['latlng']) >= 2 ? (float) $data['latlng'][0] : null;
            $lng = is_array($data['latlng'] ?? null) && count($data['latlng']) >= 2 ? (float) $data['latlng'][1] : null;

            return ['languages' => $languages, 'currency' => $currency, 'driving_side' => $drivingSide, 'lat' => $lat, 'lng' => $lng];
        } catch (\Throwable) {
            return ['languages' => null, 'currency' => null, 'driving_side' => null, 'lat' => null, 'lng' => null];
        }
    }

    /**
     * @return array{voltage: string|null, plugs: string|null}|null
     */
    private function resolveElectrical(?string $countryCode): ?array
    {
        if (blank($countryCode)) {
            return null;
        }

        $path = database_path('data/country-electrical-standards.json');
        if (! file_exists($path)) {
            return null;
        }

        $data = json_decode(file_get_contents($path), true);
        if (! is_array($data) || ! isset($data[strtolower($countryCode)])) {
            return null;
        }

        $entry = $data[strtolower($countryCode)];
        $voltage = filled($entry['voltage'] ?? null)
            ? str_replace('V', ' volts', $entry['voltage'])
            : null;

        $plugs = null;
        if (filled($entry['plug_types'] ?? null)) {
            $plugTypes = (array) $entry['plug_types'];
            $plugs = count($plugTypes) === 1
                ? 'Plug type '.$plugTypes[0]
                : 'Plug types '.implode(', ', $plugTypes);
        }

        if ($voltage === null && $plugs === null) {
            return null;
        }

        return ['voltage' => $voltage, 'plugs' => $plugs];
    }

    /**
     * @return array{months: array<string>, max_temp: array<int|null>, sunshine_hours: array<int|null>, rainfall_days: array<int|null>, sea_temp: array<int|null>|null}|null
     */
    private function resolveClimate(?string $countryCode, ?float $lat, ?float $lng): ?array
    {
        if (blank($countryCode) || $lat === null || $lng === null) {
            return null;
        }

        $cacheKey = 'climate.'.strtolower($countryCode);
        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        try {
            $response = Http::connectTimeout(3)
                ->timeout(10)
                ->get('https://archive-api.open-meteo.com/v1/archive', [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'start_date' => '2023-01-01',
                    'end_date' => '2023-12-31',
                    'daily' => 'temperature_2m_max,precipitation_sum,sunshine_duration',
                    'timezone' => 'auto',
                ]);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();
            $daily = $data['daily'] ?? null;

            if (! is_array($daily) || empty($daily['time'] ?? [])) {
                return null;
            }

            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $monthlyTemp = array_fill(0, 12, ['sum' => 0, 'count' => 0]);
            $monthlySunshine = array_fill(0, 12, ['sum' => 0, 'count' => 0]);
            $monthlyRainDays = array_fill(0, 12, 0);

            $times = $daily['time'];
            $temps = $daily['temperature_2m_max'];
            $precip = $daily['precipitation_sum'];
            $sunshine = $daily['sunshine_duration'];

            foreach ($times as $i => $date) {
                $month = (int) substr((string) $date, 5, 2) - 1;

                if (isset($temps[$i]) && $temps[$i] !== null) {
                    $monthlyTemp[$month]['sum'] += (float) $temps[$i];
                    $monthlyTemp[$month]['count']++;
                }

                if (isset($sunshine[$i]) && $sunshine[$i] !== null) {
                    $monthlySunshine[$month]['sum'] += (float) $sunshine[$i];
                    $monthlySunshine[$month]['count']++;
                }

                if (isset($precip[$i]) && $precip[$i] !== null && (float) $precip[$i] > 0) {
                    $monthlyRainDays[$month]++;
                }
            }

            $maxTemp = [];
            $sunshineHours = [];
            $rainfallDays = [];

            for ($m = 0; $m < 12; $m++) {
                $maxTemp[] = $monthlyTemp[$m]['count'] > 0
                    ? (int) round($monthlyTemp[$m]['sum'] / $monthlyTemp[$m]['count'])
                    : null;

                $sunshineHours[] = $monthlySunshine[$m]['count'] > 0
                    ? (int) round(($monthlySunshine[$m]['sum'] / $monthlySunshine[$m]['count']) / 3600)
                    : null;

                $rainfallDays[] = $monthlyRainDays[$m];
            }

            $seaTemp = $this->resolveSeaTemperature($countryCode);

            $result = [
                'months' => $months,
                'max_temp' => $maxTemp,
                'sunshine_hours' => $sunshineHours,
                'rainfall_days' => $rainfallDays,
                'sea_temp' => $seaTemp,
            ];

            Cache::put($cacheKey, $result, now()->addDay());

            return $result;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return array<int|null>|null
     */
    private function resolveSeaTemperature(?string $countryCode): ?array
    {
        if (blank($countryCode)) {
            return null;
        }

        $path = database_path('data/country-sea-temperatures.json');
        if (! file_exists($path)) {
            return null;
        }

        $data = json_decode(file_get_contents($path), true);
        if (! is_array($data) || ! isset($data[strtolower($countryCode)])) {
            return null;
        }

        $values = $data[strtolower($countryCode)];
        if (! is_array($values) || count($values) !== 12) {
            return null;
        }

        // Filter out landlocked entries (all nulls)
        $hasValue = false;
        foreach ($values as $v) {
            if ($v !== null) {
                $hasValue = true;
                break;
            }
        }

        return $hasValue ? $values : null;
    }
}
