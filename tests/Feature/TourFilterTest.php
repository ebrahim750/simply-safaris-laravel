<?php

use App\Models\Country;
use App\Models\Place;
use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\TourCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('search by name returns matching tours', function () {
    Tour::factory()->create(['name' => 'Kenya Safari Adventure', 'is_hidden' => false]);
    Tour::factory()->create(['name' => 'Tanzania Beach Holiday', 'is_hidden' => false]);
    Tour::factory()->create(['name' => 'Uganda Gorilla Trek', 'is_hidden' => false]);

    $this->get(route('tours.index', ['search' => 'Safari']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 1)
            ->where('tours.data.0.name', 'Kenya Safari Adventure')
            ->where('filters.search', 'Safari')
        );
});

test('search is case insensitive', function () {
    Tour::factory()->create(['name' => 'Kenya Safari Adventure', 'is_hidden' => false]);

    $this->get(route('tours.index', ['search' => 'safari']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('tours.data', 1)
            ->where('tours.data.0.name', 'Kenya Safari Adventure')
        );
});

test('filter by category ids returns matching tours', function () {
    $matchingTour = Tour::factory()->create(['is_hidden' => false]);
    $otherTour = Tour::factory()->create(['is_hidden' => false]);

    $category = TourCategory::factory()->create();
    $matchingTour->categories()->sync([$category->id => ['position' => 1]]);

    $this->get(route('tours.index', ['categories' => [$category->id]]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 1)
            ->where('tours.data.0.id', $matchingTour->id)
            ->where('filters.categories', [$category->id])
        );
});

test('filter by multiple category ids uses or logic', function () {
    $tourA = Tour::factory()->create(['is_hidden' => false]);
    $tourB = Tour::factory()->create(['is_hidden' => false]);
    Tour::factory()->create(['is_hidden' => false]);

    $categoryA = TourCategory::factory()->create();
    $categoryB = TourCategory::factory()->create();

    $tourA->categories()->sync([$categoryA->id => ['position' => 1]]);
    $tourB->categories()->sync([$categoryB->id => ['position' => 1]]);

    $this->get(route('tours.index', ['categories' => [$categoryA->id, $categoryB->id]]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('tours.data', 2)
        );
});

test('filter by activity ids returns matching tours', function () {
    $matchingTour = Tour::factory()->create(['is_hidden' => false]);
    Tour::factory()->create(['is_hidden' => false]);

    $activity = TourActivity::factory()->create();
    $matchingTour->activities()->sync([$activity->id => ['position' => 1]]);

    $this->get(route('tours.index', ['activities' => [$activity->id]]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 1)
            ->where('tours.data.0.id', $matchingTour->id)
            ->where('filters.activities', [$activity->id])
        );
});

test('filter by place ids returns matching tours', function () {
    $matchingTour = Tour::factory()->create(['is_hidden' => false]);
    Tour::factory()->create(['is_hidden' => false]);

    $country = Country::factory()->create();
    $place = Place::factory()->create(['country_id' => $country->id]);
    $matchingTour->places()->sync([$place->id => ['position' => 1]]);

    $this->get(route('tours.index', ['places' => [$place->id]]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 1)
            ->where('tours.data.0.id', $matchingTour->id)
            ->where('filters.places', [$place->id])
        );
});

test('filter by country ids returns tours in those countries', function () {
    $tourA = Tour::factory()->create(['is_hidden' => false]);
    $tourB = Tour::factory()->create(['is_hidden' => false]);
    Tour::factory()->create(['is_hidden' => false]);

    $countryA = Country::factory()->create();
    $placeA = Place::factory()->create(['country_id' => $countryA->id]);

    $countryB = Country::factory()->create();
    $placeB = Place::factory()->create(['country_id' => $countryB->id]);

    $tourA->places()->sync([$placeA->id => ['position' => 1]]);
    $tourB->places()->sync([$placeB->id => ['position' => 1]]);

    $this->get(route('tours.index', ['countries' => [$countryA->id, $countryB->id]]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 2)
            ->where('filters.countries', [$countryA->id, $countryB->id])
        );
});

test('filter by price range returns matching tours', function () {
    Tour::factory()->create(['price' => 500, 'is_hidden' => false]);
    Tour::factory()->create(['price' => 1500, 'is_hidden' => false]);
    Tour::factory()->create(['price' => 3000, 'is_hidden' => false]);
    Tour::factory()->create(['price' => 6000, 'is_hidden' => false]);

    $this->get(route('tours.index', ['price_min' => 1000, 'price_max' => 3500]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 2)
            ->where('filters.price.min', 1000)
            ->where('filters.price.max', 3500)
        );
});

test('filter by duration range returns matching tours', function () {
    Tour::factory()->create(['duration_days' => 3, 'is_hidden' => false]);
    Tour::factory()->create(['duration_days' => 7, 'is_hidden' => false]);
    Tour::factory()->create(['duration_days' => 14, 'is_hidden' => false]);
    Tour::factory()->create(['duration_days' => 21, 'is_hidden' => false]);

    $this->get(route('tours.index', ['duration_min' => 5, 'duration_max' => 15]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 2)
            ->where('filters.duration.min', 5)
            ->where('filters.duration.max', 15)
        );
});

test('combined filters use and logic across groups', function () {
    $matchingTour = Tour::factory()->create([
        'name' => 'Safari Special',
        'price' => 2000,
        'is_hidden' => false,
    ]);

    $wrongCategoryTour = Tour::factory()->create([
        'name' => 'Safari Special 2',
        'price' => 2000,
        'is_hidden' => false,
    ]);

    $wrongPriceTour = Tour::factory()->create([
        'name' => 'Safari Special',
        'price' => 5000,
        'is_hidden' => false,
    ]);

    $category = TourCategory::factory()->create();
    $country = Country::factory()->create();
    $place = Place::factory()->create(['country_id' => $country->id]);

    $matchingTour->categories()->sync([$category->id => ['position' => 1]]);
    $matchingTour->places()->sync([$place->id => ['position' => 1]]);

    $wrongCategoryTour->places()->sync([$place->id => ['position' => 1]]);

    $wrongPriceTour->categories()->sync([$category->id => ['position' => 1]]);
    $wrongPriceTour->places()->sync([$place->id => ['position' => 1]]);

    $this->get(route('tours.index', [
        'search' => 'Safari',
        'categories' => [$category->id],
        'countries' => [$country->id],
        'price_max' => 3000,
    ]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 1)
            ->where('tours.data.0.id', $matchingTour->id)
        );
});

test('empty filter results are handled gracefully', function () {
    Tour::factory()->create(['name' => 'Beach Holiday', 'is_hidden' => false]);

    $this->get(route('tours.index', ['search' => 'Mountain']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 0)
            ->where('tours.total', 0)
        );
});

test('filter options are passed as props', function () {
    Country::factory()->count(2)->create();
    TourCategory::factory()->count(2)->create();
    TourActivity::factory()->count(2)->create();
    Tour::factory()->count(3)->create(['is_hidden' => false]);

    $this->get(route('tours.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('countries')
            ->has('categories')
            ->has('activities')
            ->has('filters')
            ->where('filters.search', null)
            ->where('filters.categories', [])
            ->where('filters.activities', [])
            ->where('filters.places', [])
            ->where('filters.countries', [])
            ->where('filters.price.min', 100)
            ->where('filters.price.max', 5000)
            ->where('filters.duration.min', 1)
            ->where('filters.duration.max', 30)
        );
});

test('hidden tours are excluded even when filtering', function () {
    Tour::factory()->create(['name' => 'Safari Hidden', 'is_hidden' => true]);
    Tour::factory()->create(['name' => 'Safari Visible', 'is_hidden' => false]);

    $this->get(route('tours.index', ['search' => 'Safari']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('tours.data', 1)
            ->where('tours.data.0.name', 'Safari Visible')
        );
});
