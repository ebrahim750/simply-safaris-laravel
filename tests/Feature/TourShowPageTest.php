<?php

use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('tour show page renders the tour and seasonal recommendations', function () {
    $tour = Tour::factory()->create(['is_hidden' => false]);
    $seasonalTour = Tour::factory()->create([
        'is_hidden' => false,
        'is_featured_as_seasonal_recommendation' => true,
    ]);

    $this->get(route('tours.show', $tour))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Show')
            ->has('tour')
            ->has('seasonalRecommendations', 1)
            ->where('seasonalRecommendations.0.id', $seasonalTour->id)
        );
});

test('current tour is excluded from seasonal recommendations', function () {
    $tour = Tour::factory()->create([
        'is_hidden' => false,
        'is_featured_as_seasonal_recommendation' => true,
    ]);

    $this->get(route('tours.show', $tour))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Show')
            ->has('seasonalRecommendations', 0)
        );
});

test('hidden seasonal recommendations are excluded', function () {
    $tour = Tour::factory()->create(['is_hidden' => false]);
    Tour::factory()->create([
        'is_hidden' => true,
        'is_featured_as_seasonal_recommendation' => true,
    ]);

    $this->get(route('tours.show', $tour))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Show')
            ->has('seasonalRecommendations', 0)
        );
});
