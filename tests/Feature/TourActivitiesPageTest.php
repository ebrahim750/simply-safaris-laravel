<?php

use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin users can view the activities page', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $activity = TourActivity::factory()->create([
        'name' => 'Game Drives',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.tours.activities'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/tours/Activities')
            ->where('activities.0.name', $activity->name)
            ->where('activities.0.created_at_display', $activity->created_at->format('M j, Y g:i A'))
        );
});

test('admin users can create activities', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $response = $this->actingAs($adminUser)
        ->post(route('admin.tours.activities.store'), [
            'name' => 'Bush Breakfast',
        ]);

    $response->assertRedirect(route('admin.tours.activities'));

    $this->assertDatabaseHas('tour_activities', [
        'name' => 'Bush Breakfast',
    ]);
});

test('activity creation rejects duplicate names', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $existingActivity = TourActivity::factory()->create([
        'name' => 'Sundowner',
    ]);

    $response = $this->actingAs($adminUser)
        ->from(route('admin.tours.activities'))
        ->post(route('admin.tours.activities.store'), [
            'name' => $existingActivity->name,
        ]);

    $response->assertRedirect(route('admin.tours.activities'));
    $response->assertSessionHasErrors(['name']);
});

test('admin users can rename activities', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $activity = TourActivity::factory()->create([
        'name' => 'Walking Safari',
    ]);

    $response = $this->actingAs($adminUser)
        ->patch(route('admin.tours.activities.update', $activity), [
            'name' => 'Guided Walking Safari',
        ]);

    $response->assertRedirect(route('admin.tours.activities'));

    expect($activity->refresh()->name)->toBe('Guided Walking Safari');
});

test('activity rename rejects duplicate names', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $activity = TourActivity::factory()->create([
        'name' => 'Boat Cruise',
    ]);
    $otherActivity = TourActivity::factory()->create([
        'name' => 'Hot Air Balloon',
    ]);

    $response = $this->actingAs($adminUser)
        ->from(route('admin.tours.activities'))
        ->patch(route('admin.tours.activities.update', $activity), [
            'name' => $otherActivity->name,
        ]);

    $response->assertRedirect(route('admin.tours.activities'));
    $response->assertSessionHasErrors(['name']);

    expect($activity->refresh()->name)->toBe('Boat Cruise');
});

test('admin users can delete activities', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $activity = TourActivity::factory()->create([
        'name' => 'Night Drive',
    ]);

    $response = $this->actingAs($adminUser)
        ->delete(route('admin.tours.activities.destroy', $activity));

    $response->assertRedirect(route('admin.tours.activities'));

    $this->assertModelMissing($activity);
});

test('admin users can delete activities that are assigned to tours', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tour = Tour::factory()->create();
    $activity = TourActivity::factory()->create([
        'name' => 'Bush Dinner',
    ]);

    $tour->activities()->attach($activity->id, ['position' => 1]);

    $response = $this->actingAs($adminUser)
        ->delete(route('admin.tours.activities.destroy', $activity));

    $response->assertRedirect(route('admin.tours.activities'));

    $this->assertModelMissing($activity);
    $this->assertDatabaseMissing('tour_activity_pivot', [
        'tour_id' => $tour->id,
        'tour_activity_id' => $activity->id,
    ]);
});

test('non admin users cannot create activities', function () {
    $user = User::factory()->create([
        'account_type' => 'customer',
    ]);

    $this->actingAs($user)
        ->post(route('admin.tours.activities.store'), [
            'name' => 'Blocked Activity',
        ])
        ->assertForbidden();
});

test('non admin users cannot delete activities', function () {
    $user = User::factory()->create([
        'account_type' => 'customer',
    ]);
    $activity = TourActivity::factory()->create();

    $this->actingAs($user)
        ->delete(route('admin.tours.activities.destroy', $activity))
        ->assertForbidden();

    $this->assertModelExists($activity);
});

test('non admin users cannot rename activities', function () {
    $user = User::factory()->create([
        'account_type' => 'customer',
    ]);
    $activity = TourActivity::factory()->create();

    $this->actingAs($user)
        ->patch(route('admin.tours.activities.update', $activity), [
            'name' => 'Blocked Rename',
        ])
        ->assertForbidden();
});
