<?php

use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\TourCategory;
use App\Models\TourExclusion;
use App\Models\TourInclusion;
use App\Models\TourItineraryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('tours table has the expected columns', function () {
    expect(Schema::hasTable('tours'))->toBeTrue();
    expect(Schema::hasColumns('tours', [
        'id',
        'name',
        'price',
        'main_image',
        'gallery_images',
        'external_url',
        'external_link_button_text',
        'duration_days',
        'overview',
        'pricing_detailed',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('tour categories and activities tables have the expected columns', function () {
    expect(Schema::hasTable('tour_categories'))->toBeTrue();
    expect(Schema::hasColumns('tour_categories', [
        'id',
        'name',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasTable('tour_activities'))->toBeTrue();
    expect(Schema::hasColumns('tour_activities', [
        'id',
        'name',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('tour activity pivot table has the expected columns', function () {
    expect(Schema::hasTable('tour_activity_pivot'))->toBeTrue();
    expect(Schema::hasColumns('tour_activity_pivot', [
        'id',
        'tour_id',
        'tour_activity_id',
        'position',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('tour category pivot table has the expected columns', function () {
    expect(Schema::hasTable('tour_category_pivot'))->toBeTrue();
    expect(Schema::hasColumns('tour_category_pivot', [
        'id',
        'tour_id',
        'tour_category_id',
        'position',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('destinations and destinationables tables have the expected columns', function () {
    expect(Schema::hasTable('destinations'))->toBeTrue();
    expect(Schema::hasColumns('destinations', [
        'id',
        'name',
        'slug',
        'type',
        'parent_id',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasTable('destinationables'))->toBeTrue();
    expect(Schema::hasColumns('destinationables', [
        'id',
        'destination_id',
        'destinationable_type',
        'destinationable_id',
        'position',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('site settings table has the expected columns', function () {
    expect(Schema::hasTable('site_settings'))->toBeTrue();
    expect(Schema::hasColumns('site_settings', [
        'id',
        'site_name',
        'site_tagline',
        'currency',
        'primary_color',
        'secondary_color',
        'accent_color',
        'logo_path',
        'favicon_path',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('tour itinerary items table has the expected columns', function () {
    expect(Schema::hasTable('tour_itinerary_items'))->toBeTrue();
    expect(Schema::hasColumns('tour_itinerary_items', [
        'id',
        'tour_id',
        'position',
        'title',
        'content',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('tour inclusions and exclusions tables have the expected columns', function () {
    expect(Schema::hasTable('tour_inclusions'))->toBeTrue();
    expect(Schema::hasColumns('tour_inclusions', [
        'id',
        'tour_id',
        'position',
        'text',
        'created_at',
        'updated_at',
    ]))->toBeTrue();

    expect(Schema::hasTable('tour_exclusions'))->toBeTrue();
    expect(Schema::hasColumns('tour_exclusions', [
        'id',
        'tour_id',
        'position',
        'text',
        'created_at',
        'updated_at',
    ]))->toBeTrue();
});

test('tour gallery images are cast to an array', function () {
    $tour = Tour::factory()->create([
        'gallery_images' => [
            'https://example.com/gallery-1.jpg',
            'https://example.com/gallery-2.jpg',
        ],
    ]);

    expect($tour->gallery_images)->toBeArray()
        ->and($tour->gallery_images)->toHaveCount(2)
        ->and($tour->price)->toBeString();
});

test('tour itinerary items are ordered by position', function () {
    $tour = Tour::factory()->create();

    TourItineraryItem::factory()->create([
        'tour_id' => $tour->id,
        'position' => 2,
        'title' => 'Second',
    ]);

    TourItineraryItem::factory()->create([
        'tour_id' => $tour->id,
        'position' => 1,
        'title' => 'First',
    ]);

    expect($tour->itineraryItems->pluck('title')->all())->toBe(['First', 'Second']);
});

test('tour inclusions and exclusions are ordered by position', function () {
    $tour = Tour::factory()->create();

    TourInclusion::factory()->create([
        'tour_id' => $tour->id,
        'position' => 2,
        'text' => 'Included second',
    ]);

    TourInclusion::factory()->create([
        'tour_id' => $tour->id,
        'position' => 1,
        'text' => 'Included first',
    ]);

    TourExclusion::factory()->create([
        'tour_id' => $tour->id,
        'position' => 2,
        'text' => 'Excluded second',
    ]);

    TourExclusion::factory()->create([
        'tour_id' => $tour->id,
        'position' => 1,
        'text' => 'Excluded first',
    ]);

    expect($tour->inclusions->pluck('text')->all())->toBe(['Included first', 'Included second'])
        ->and($tour->exclusions->pluck('text')->all())->toBe(['Excluded first', 'Excluded second']);
});

test('tour categories and activities are ordered by pivot position', function () {
    $tour = Tour::factory()->create();
    $categoryTwo = TourCategory::factory()->create(['name' => 'Relaxation']);
    $categoryOne = TourCategory::factory()->create(['name' => 'Adventure']);

    $activityTwo = TourActivity::factory()->create(['name' => 'Kayaking']);
    $activityOne = TourActivity::factory()->create(['name' => 'Hiking']);

    $tour->categories()->attach($categoryTwo->id, ['position' => 2]);
    $tour->categories()->attach($categoryOne->id, ['position' => 1]);
    $tour->activities()->attach($activityTwo->id, ['position' => 2]);
    $tour->activities()->attach($activityOne->id, ['position' => 1]);

    expect($tour->categories->pluck('name')->all())->toBe(['Adventure', 'Relaxation'])
        ->and($tour->activities->pluck('name')->all())->toBe(['Hiking', 'Kayaking']);
});

test('tour destinations are ordered by pivot position', function () {
    $tour = Tour::factory()->create();
    $destinationTwo = Destination::factory()->create(['name' => 'Zanzibar', 'slug' => 'zanzibar', 'type' => 'place']);
    $destinationOne = Destination::factory()->create(['name' => 'Tanzania', 'slug' => 'tanzania', 'type' => 'country']);

    $tour->destinations()->attach($destinationTwo->id, ['position' => 2]);
    $tour->destinations()->attach($destinationOne->id, ['position' => 1]);

    expect($tour->destinations->pluck('name')->all())->toBe(['Tanzania', 'Zanzibar']);
});
