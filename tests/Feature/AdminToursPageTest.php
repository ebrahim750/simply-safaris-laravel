<?php

use App\Models\SiteSetting;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin users can view the tours page with main image data', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    SiteSetting::query()->create([
        'site_name' => 'Example Site',
        'currency' => 'EUR',
    ]);

    $tour = Tour::factory()->create([
        'name' => 'Savannah Escape',
        'main_image' => 'https://example.com/tours/savannah-escape.jpg',
        'price' => 12500.50,
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.tours'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/tours/Tours')
            ->where('tours.0.name', $tour->name)
            ->where('tours.0.main_image', $tour->main_image)
            ->where('tours.0.price_amount', 12500.5)
            ->where('tours.0.price', '€12,500')
        );
});

test('admin tours page resolves stored main image paths to public urls', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $tour = Tour::factory()->create([
        'main_image' => 'tours/savannah-escape.jpg',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.tours'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('tours.0.name', $tour->name)
            ->where('tours.0.main_image', Storage::disk('public')->url('tours/savannah-escape.jpg'))
        );
});

test('admin users can toggle a tour hidden status', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $tour = Tour::factory()->create([
        'is_hidden' => false,
    ]);

    $this->actingAs($adminUser)
        ->patch(route('admin.tours.hidden.toggle', $tour))
        ->assertRedirect();

    expect($tour->refresh()->is_hidden)->toBeTrue();
});
