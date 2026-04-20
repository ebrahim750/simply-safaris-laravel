<?php

use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin users can view the categories page', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $category = TourCategory::factory()->create([
        'name' => 'Adventure',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.tours.categories'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/tours/Categories')
            ->where('categories.0.name', $category->name)
            ->where('categories.0.created_at_display', $category->created_at->format('M j, Y g:i A'))
        );
});

test('admin users can create categories', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $response = $this->actingAs($adminUser)
        ->post(route('admin.tours.categories.store'), [
            'name' => 'Wildlife',
        ]);

    $response->assertRedirect(route('admin.tours.categories'));

    $this->assertDatabaseHas('tour_categories', [
        'name' => 'Wildlife',
    ]);
});

test('category creation rejects duplicate names', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $existingCategory = TourCategory::factory()->create([
        'name' => 'Relaxation',
    ]);

    $response = $this->actingAs($adminUser)
        ->from(route('admin.tours.categories'))
        ->post(route('admin.tours.categories.store'), [
            'name' => $existingCategory->name,
        ]);

    $response->assertRedirect(route('admin.tours.categories'));
    $response->assertSessionHasErrors(['name']);
});

test('admin users can rename categories', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $category = TourCategory::factory()->create([
        'name' => 'Family',
    ]);

    $response = $this->actingAs($adminUser)
        ->patch(route('admin.tours.categories.update', $category), [
            'name' => 'Family Friendly',
        ]);

    $response->assertRedirect(route('admin.tours.categories'));

    expect($category->refresh()->name)->toBe('Family Friendly');
});

test('category rename rejects duplicate names', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $category = TourCategory::factory()->create([
        'name' => 'Culture',
    ]);
    $otherCategory = TourCategory::factory()->create([
        'name' => 'Luxury',
    ]);

    $response = $this->actingAs($adminUser)
        ->from(route('admin.tours.categories'))
        ->patch(route('admin.tours.categories.update', $category), [
            'name' => $otherCategory->name,
        ]);

    $response->assertRedirect(route('admin.tours.categories'));
    $response->assertSessionHasErrors(['name']);

    expect($category->refresh()->name)->toBe('Culture');
});

test('admin users can delete categories', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $category = TourCategory::factory()->create([
        'name' => 'Nature',
    ]);

    $response = $this->actingAs($adminUser)
        ->delete(route('admin.tours.categories.destroy', $category));

    $response->assertRedirect(route('admin.tours.categories'));

    $this->assertModelMissing($category);
});

test('admin users can delete categories that are assigned to tours', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tour = Tour::factory()->create();
    $category = TourCategory::factory()->create([
        'name' => 'Safari Classics',
    ]);

    $tour->categories()->attach($category->id, ['position' => 1]);

    $response = $this->actingAs($adminUser)
        ->delete(route('admin.tours.categories.destroy', $category));

    $response->assertRedirect(route('admin.tours.categories'));

    $this->assertModelMissing($category);
    $this->assertDatabaseMissing('tour_category_pivot', [
        'tour_id' => $tour->id,
        'tour_category_id' => $category->id,
    ]);
});

test('non admin users cannot create categories', function () {
    $user = User::factory()->create([
        'account_type' => 'customer',
    ]);

    $this->actingAs($user)
        ->post(route('admin.tours.categories.store'), [
            'name' => 'Blocked Category',
        ])
        ->assertForbidden();
});

test('non admin users cannot rename categories', function () {
    $user = User::factory()->create([
        'account_type' => 'customer',
    ]);
    $category = TourCategory::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.tours.categories.update', $category), [
            'name' => 'Blocked Rename',
        ])
        ->assertForbidden();
});

test('non admin users cannot delete categories', function () {
    $user = User::factory()->create([
        'account_type' => 'customer',
    ]);
    $category = TourCategory::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.tours.categories.destroy', $category))
        ->assertForbidden();

    $this->assertModelExists($category);
});
