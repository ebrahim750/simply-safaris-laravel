<?php

use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('tours index page renders paginated tours', function () {
    Tour::factory()->count(15)->create(['is_hidden' => false]);

    $this->get(route('tours.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 9)
            ->where('tours.current_page', 1)
            ->where('tours.per_page', 9)
            ->where('tours.total', 15)
            ->has('tours.links')
        );
});

test('tours index page paginates to second page', function () {
    Tour::factory()->count(15)->create(['is_hidden' => false]);

    $this->get(route('tours.index', ['page' => 2]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 6)
            ->where('tours.current_page', 2)
        );
});

test('pagination metadata indicates a single page when tours are nine or fewer', function () {
    Tour::factory()->count(9)->create(['is_hidden' => false]);

    $this->get(route('tours.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 9)
            ->where('tours.last_page', 1)
        );
});

test('hidden tours are excluded from tours index page', function () {
    Tour::factory()->count(3)->create(['is_hidden' => false]);
    Tour::factory()->count(2)->create(['is_hidden' => true]);

    $this->get(route('tours.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tours/Index')
            ->has('tours.data', 3)
            ->where('tours.total', 3)
        );
});
