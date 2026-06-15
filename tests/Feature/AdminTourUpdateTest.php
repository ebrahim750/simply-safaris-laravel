<?php

use App\Models\Country;
use App\Models\Place;
use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\TourCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin users can view the tour edit page', function () {
    Storage::fake('public');

    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tour = Tour::factory()->create([
        'gallery_images' => ['tours/1/gallery/existing.jpg'],
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.tours.edit', $tour))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/tours/Edit')
            ->where('tour.id', $tour->id)
            ->has('tour.gallery_images')
        );
});

test('updating a tour preserves kept gallery images and deletes removed ones', function () {
    Storage::fake('public');

    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tour = Tour::factory()->create([
        'name' => 'Original Name',
    ]);
    $tour->forceFill([
        'gallery_images' => [
            'tours/'.$tour->id.'/gallery/keep-1.jpg',
            'tours/'.$tour->id.'/gallery/keep-2.jpg',
            'tours/'.$tour->id.'/gallery/remove-1.jpg',
        ],
    ])->save();

    Storage::disk('public')->put('tours/'.$tour->id.'/gallery/keep-1.jpg', 'image1');
    Storage::disk('public')->put('tours/'.$tour->id.'/gallery/keep-2.jpg', 'image2');
    Storage::disk('public')->put('tours/'.$tour->id.'/gallery/remove-1.jpg', 'image3');

    $category = TourCategory::factory()->create();
    $activity = TourActivity::factory()->create();
    $country = Country::factory()->create();
    $place = Place::factory()->create(['country_id' => $country->id]);

    $publicUrl = Storage::disk('public')->url('');

    $response = $this->actingAs($adminUser)
        ->patch(route('admin.tours.update', $tour), [
            'name' => 'Updated Name',
            'price' => '1000',
            'external_url' => null,
            'external_link_button_text' => null,
            'show_external_booking' => '0',
            'duration_days' => '5',
            'overview' => 'Updated overview',
            'pricing_detailed' => null,
            'is_hidden' => '0',
            'is_featured_on_home' => '0',
            'is_featured_as_seasonal_recommendation' => '0',
            'category_ids' => [$category->id],
            'activity_ids' => [$activity->id],
            'place_ids' => [$place->id],
            'itinerary_items' => [],
            'inclusions' => [],
            'exclusions' => [],
            'kept_gallery_images' => [
                $publicUrl.'tours/'.$tour->id.'/gallery/keep-1.jpg',
                $publicUrl.'tours/'.$tour->id.'/gallery/keep-2.jpg',
            ],
            'gallery_images' => [
                UploadedFile::fake()->image('new-gallery.jpg'),
            ],
        ], ['Content-Type' => 'multipart/form-data']);

    $response->assertRedirect(route('admin.tours.edit', $tour));
    $response->assertSessionHas('status', 'Tour updated.');

    $tour->refresh();

    expect($tour->name)->toBe('Updated Name')
        ->and($tour->gallery_images)->toHaveCount(3);

    // Kept images should still exist
    Storage::disk('public')->assertExists('tours/'.$tour->id.'/gallery/keep-1.jpg');
    Storage::disk('public')->assertExists('tours/'.$tour->id.'/gallery/keep-2.jpg');

    // Removed image should be deleted
    Storage::disk('public')->assertMissing('tours/'.$tour->id.'/gallery/remove-1.jpg');

    // New image should exist
    expect($tour->gallery_images[2])->toStartWith('tours/'.$tour->id.'/gallery/');
    Storage::disk('public')->assertExists($tour->gallery_images[2]);
});

test('updating a tour without gallery changes preserves existing images', function () {
    Storage::fake('public');

    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tour = Tour::factory()->create([
        'name' => 'Original Name',
    ]);
    $tour->forceFill([
        'gallery_images' => [
            'tours/'.$tour->id.'/gallery/existing.jpg',
        ],
    ])->save();

    Storage::disk('public')->put('tours/'.$tour->id.'/gallery/existing.jpg', 'image');

    $category = TourCategory::factory()->create();
    $activity = TourActivity::factory()->create();
    $country = Country::factory()->create();
    $place = Place::factory()->create(['country_id' => $country->id]);

    $response = $this->actingAs($adminUser)
        ->patch(route('admin.tours.update', $tour), [
            'name' => 'Updated Name Only',
            'price' => '1000',
            'external_url' => null,
            'external_link_button_text' => null,
            'show_external_booking' => '0',
            'duration_days' => '5',
            'overview' => 'Updated overview',
            'pricing_detailed' => null,
            'is_hidden' => '0',
            'is_featured_on_home' => '0',
            'is_featured_as_seasonal_recommendation' => '0',
            'category_ids' => [$category->id],
            'activity_ids' => [$activity->id],
            'place_ids' => [$place->id],
            'itinerary_items' => [],
            'inclusions' => [],
            'exclusions' => [],
        ], ['Content-Type' => 'multipart/form-data']);

    $response->assertRedirect(route('admin.tours.edit', $tour));

    $tour->refresh();

    expect($tour->name)->toBe('Updated Name Only')
        ->and($tour->gallery_images)->toHaveCount(1)
        ->and($tour->gallery_images[0])->toBe('tours/'.$tour->id.'/gallery/existing.jpg');

    Storage::disk('public')->assertExists('tours/'.$tour->id.'/gallery/existing.jpg');
});

test('updating a tour removes all existing images when none are kept', function () {
    Storage::fake('public');

    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tour = Tour::factory()->create();
    $tour->forceFill([
        'gallery_images' => [
            'tours/'.$tour->id.'/gallery/old-1.jpg',
            'tours/'.$tour->id.'/gallery/old-2.jpg',
        ],
    ])->save();

    Storage::disk('public')->put('tours/'.$tour->id.'/gallery/old-1.jpg', 'image1');
    Storage::disk('public')->put('tours/'.$tour->id.'/gallery/old-2.jpg', 'image2');

    $category = TourCategory::factory()->create();
    $activity = TourActivity::factory()->create();
    $country = Country::factory()->create();
    $place = Place::factory()->create(['country_id' => $country->id]);

    $response = $this->actingAs($adminUser)
        ->patch(route('admin.tours.update', $tour), [
            'name' => 'Updated Name',
            'price' => '1000',
            'external_url' => null,
            'external_link_button_text' => null,
            'show_external_booking' => '0',
            'duration_days' => '5',
            'overview' => 'Updated overview',
            'pricing_detailed' => null,
            'is_hidden' => '0',
            'is_featured_on_home' => '0',
            'is_featured_as_seasonal_recommendation' => '0',
            'category_ids' => [$category->id],
            'activity_ids' => [$activity->id],
            'place_ids' => [$place->id],
            'itinerary_items' => [],
            'inclusions' => [],
            'exclusions' => [],
            'kept_gallery_images' => [],
        ], ['Content-Type' => 'multipart/form-data']);

    $response->assertRedirect(route('admin.tours.edit', $tour));

    $tour->refresh();

    expect($tour->gallery_images)->toBe([]);

    Storage::disk('public')->assertMissing('tours/'.$tour->id.'/gallery/old-1.jpg');
    Storage::disk('public')->assertMissing('tours/'.$tour->id.'/gallery/old-2.jpg');
});
