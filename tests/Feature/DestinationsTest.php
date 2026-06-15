<?php

use App\Models\Country;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

describe('Destinations', function () {
    it('can render destinations index page', function () {
        Country::factory()
            ->count(3)
            ->has(Place::factory()->count(2))
            ->create();

        $response = $this->get(route('destinations.index'));

        $response->assertStatus(200);
    });

    it('can render country page', function () {
        $country = Country::factory()
            ->has(Place::factory()->count(2))
            ->create();

        $response = $this->get(route('destinations.country', $country));

        $response->assertStatus(200);
    });

    it('shares destinations data in inertia props', function () {
        Country::factory()
            ->count(2)
            ->has(Place::factory()->count(2))
            ->create();

        $response = $this->get('/');

        $response->assertInertia(fn ($page) => $page
            ->has('destinations', 2)
            ->has('destinations.0.places', 2)
        );
    });

    it('includes climate data for countries with known country codes', function () {
        Cache::forget('climate.ke');
        Cache::forget('climate.fallback.ke');

        $country = Country::factory()->create([
            'name' => 'Kenya',
            'slug' => 'kenya',
            'country_code' => 'ke',
        ]);

        $response = $this->get(route('destinations.country', $country));

        $response->assertInertia(fn ($page) => $page
            ->has('country.climate')
            ->has('country.climate.months', 12)
            ->has('country.climate.max_temp', 12)
            ->has('country.climate.sunshine_hours', 12)
            ->has('country.climate.rainfall_days', 12)
        );
    });

    it('includes climate data for countries not hardcoded in the app', function () {
        Cache::forget('climate.ug');
        Cache::forget('climate.fallback.ug');

        $country = Country::factory()->create([
            'name' => 'Uganda',
            'slug' => 'uganda',
            'country_code' => 'ug',
        ]);

        $response = $this->get(route('destinations.country', $country));

        $response->assertInertia(fn ($page) => $page
            ->has('country.climate')
            ->has('country.climate.months', 12)
            ->has('country.climate.max_temp', 12)
            ->has('country.climate.sunshine_hours', 12)
            ->has('country.climate.rainfall_days', 12)
        );
    });

    it('falls back to stale climate data when open meteo fails', function () {
        Cache::forget('climate.ke');
        Cache::put('climate.fallback.ke', [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'max_temp' => [30, 31, 30, 29, 28, 27, 26, 27, 28, 29, 30, 30],
            'sunshine_hours' => [8, 8, 8, 8, 8, 8, 7, 7, 8, 8, 8, 8],
            'rainfall_days' => [5, 5, 8, 12, 10, 6, 5, 5, 5, 7, 9, 7],
            'sea_temp' => null,
        ]);

        Http::fake([
            'archive-api.open-meteo.com/*' => Http::response('', 500),
        ]);

        $country = Country::factory()->create([
            'name' => 'Kenya',
            'slug' => 'kenya',
            'country_code' => 'ke',
        ]);

        $response = $this->get(route('destinations.country', $country));

        $response->assertInertia(fn ($page) => $page
            ->has('country.climate')
            ->where('country.climate.max_temp', [30, 31, 30, 29, 28, 27, 26, 27, 28, 29, 30, 30])
        );
    });
});
