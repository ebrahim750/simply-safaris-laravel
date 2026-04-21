<?php

use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\TourCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin users can view the new tour page', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $category = TourCategory::factory()->create([
        'name' => 'Adventure',
    ]);
    $activity = TourActivity::factory()->create([
        'name' => 'Game Drives',
    ]);
    $destination = Destination::factory()->create([
        'name' => 'Tanzania',
        'slug' => 'tanzania',
        'type' => 'country',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.tours.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/tours/Create')
            ->where('categories.0.id', $category->id)
            ->where('categories.0.name', $category->name)
            ->where('activities.0.id', $activity->id)
            ->where('activities.0.name', $activity->name)
            ->where('destinations.0.id', $destination->id)
            ->where('destinations.0.name', $destination->name)
            ->where('destinations.0.type', $destination->type)
            ->where('destinations.0.parent_id', $destination->parent_id)
        );
});

test('admin users can create a tour with uploaded images and related content', function () {
    Storage::fake('public');

    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $categoryOne = TourCategory::factory()->create([
        'name' => 'Adventure',
    ]);
    $categoryTwo = TourCategory::factory()->create([
        'name' => 'Luxury',
    ]);
    $activityOne = TourActivity::factory()->create([
        'name' => 'Game Drives',
    ]);
    $activityTwo = TourActivity::factory()->create([
        'name' => 'Bush Walks',
    ]);
    $destinationOne = Destination::factory()->create([
        'name' => 'Kenya',
        'slug' => 'kenya',
        'type' => 'country',
    ]);
    $destinationTwo = Destination::factory()->create([
        'name' => 'Masai Mara',
        'slug' => 'masai-mara',
        'type' => 'place',
    ]);

    $response = $this->actingAs($adminUser)
        ->post(route('admin.tours.store'), [
            'name' => 'Great Migration Signature Safari',
            'price' => '12500.50',
            'main_image' => UploadedFile::fake()->image('main-image.jpg'),
            'gallery_images' => [
                UploadedFile::fake()->image('gallery-1.jpg'),
                UploadedFile::fake()->image('gallery-2.jpg'),
            ],
            'external_url' => 'https://example.com/tours/great-migration',
            'external_link_button_text' => 'Book this safari',
            'duration_days' => '9',
            'overview' => 'A premium East Africa migration experience.',
            'pricing_detailed' => 'Based on double occupancy and season.',
            'is_hidden' => '0',
            'is_featured_on_home' => '1',
            'is_featured_as_seasonal_recommendation' => '1',
            'category_ids' => [$categoryTwo->id, $categoryOne->id],
            'activity_ids' => [$activityTwo->id, $activityOne->id],
            'destination_ids' => [$destinationTwo->id, $destinationOne->id],
            'itinerary_items' => [
                ['title' => 'Arrival in Nairobi', 'content' => 'Airport meet and transfer.'],
                ['title' => '', 'content' => ''],
                ['title' => 'Fly to camp', 'content' => 'Afternoon game drive on arrival.'],
            ],
            'inclusions' => [
                ['text' => 'Luxury tented accommodation'],
                ['text' => ''],
                ['text' => 'All park fees'],
            ],
            'exclusions' => [
                ['text' => 'International flights'],
                ['text' => ''],
                ['text' => 'Travel insurance'],
            ],
        ], ['Content-Type' => 'multipart/form-data']);

    $response->assertRedirect(route('admin.tours'));
    $response->assertSessionHas('status', 'Tour created.');

    $tour = Tour::query()->with([
        'categories',
        'activities',
        'destinations',
        'itineraryItems',
        'inclusions',
        'exclusions',
    ])->sole();

    expect($tour->name)->toBe('Great Migration Signature Safari')
        ->and($tour->price)->toBe('12500.50')
        ->and($tour->external_url)->toBe('https://example.com/tours/great-migration')
        ->and($tour->external_link_button_text)->toBe('Book this safari')
        ->and($tour->duration_days)->toBe(9)
        ->and($tour->overview)->toBe('A premium East Africa migration experience.')
        ->and($tour->pricing_detailed)->toBe('Based on double occupancy and season.')
        ->and($tour->is_hidden)->toBeFalse()
        ->and($tour->is_featured_on_home)->toBeTrue()
        ->and($tour->is_featured_as_seasonal_recommendation)->toBeTrue()
        ->and($tour->gallery_images)->toHaveCount(2)
        ->and($tour->categories->pluck('id')->all())->toBe([$categoryTwo->id, $categoryOne->id])
        ->and($tour->activities->pluck('id')->all())->toBe([$activityTwo->id, $activityOne->id])
        ->and($tour->destinations->pluck('id')->all())->toBe([$destinationTwo->id, $destinationOne->id])
        ->and($tour->itineraryItems->pluck('position')->all())->toBe([1, 2])
        ->and($tour->itineraryItems->pluck('title')->all())->toBe(['Arrival in Nairobi', 'Fly to camp'])
        ->and($tour->inclusions->pluck('position')->all())->toBe([1, 2])
        ->and($tour->inclusions->pluck('text')->all())->toBe(['Luxury tented accommodation', 'All park fees'])
        ->and($tour->exclusions->pluck('position')->all())->toBe([1, 2])
        ->and($tour->exclusions->pluck('text')->all())->toBe(['International flights', 'Travel insurance']);

    expect($tour->main_image)->toStartWith('tours/'.$tour->id.'/');
    Storage::disk('public')->assertExists($tour->main_image);

    foreach ($tour->gallery_images as $galleryImage) {
        expect($galleryImage)->toStartWith('tours/'.$tour->id.'/gallery/');
        Storage::disk('public')->assertExists($galleryImage);
    }
});

test('admin tour creation validates required fields, uploads, and related ids', function () {
    Storage::fake('public');

    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $response = $this->actingAs($adminUser)
        ->from(route('admin.tours.create'))
        ->post(route('admin.tours.store'), [
            'name' => '   ',
            'price' => '-10',
            'main_image' => UploadedFile::fake()->create('main-image.pdf', 32, 'application/pdf'),
            'gallery_images' => [
                UploadedFile::fake()->create('gallery.txt', 8, 'text/plain'),
            ],
            'external_url' => 'not-a-url',
            'duration_days' => '0',
            'category_ids' => [999999],
            'activity_ids' => [999999],
            'destination_ids' => [999999],
            'itinerary_items' => [
                ['title' => '', 'content' => 'Content without a title'],
            ],
            'inclusions' => [
                ['text' => ''],
            ],
            'exclusions' => [
                ['text' => ''],
            ],
        ], ['Content-Type' => 'multipart/form-data']);

    $response->assertRedirect(route('admin.tours.create'));
    $response->assertSessionHasErrors([
        'name',
        'price',
        'main_image',
        'gallery_images.0',
        'external_url',
        'duration_days',
        'category_ids.0',
        'activity_ids.0',
        'destination_ids.0',
        'itinerary_items.0.title',
    ]);

    expect(Tour::query()->count())->toBe(0);
});
