<?php

use App\Models\Country;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
});
