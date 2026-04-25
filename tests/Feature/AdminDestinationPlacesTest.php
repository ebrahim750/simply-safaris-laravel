<?php

use App\Models\Country;
use App\Models\Place;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin users can view places page', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $country = Country::factory()->create([
        'name' => 'Kenya',
    ]);
    $place = Place::factory()->create([
        'country_id' => $country->id,
        'name' => 'Masai Mara',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.destinations.places'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/destinations/places/Index')
            ->where('places.0.id', $place->id)
            ->where('places.0.name', $place->name)
            ->where('places.0.country_name', 'Kenya')
        );
});

test('admin users can create places', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $country = Country::factory()->create([
        'name' => 'Tanzania',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.destinations.places.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/destinations/places/Create')
            ->where('countries.0.id', $country->id)
            ->where('countries.0.name', 'Tanzania')
        );

    $response = $this->actingAs($adminUser)
        ->post(route('admin.destinations.places.store'), [
            'country_id' => $country->id,
            'name' => 'Ngorongoro',
            'overview' => 'A volcanic caldera safari destination.',
        ]);

    $response->assertRedirect(route('admin.destinations.places'));
    $response->assertSessionHas('status', 'Place created.');

    $this->assertDatabaseHas('places', [
        'country_id' => $country->id,
        'name' => 'Ngorongoro',
        'slug' => 'ngorongoro',
        'overview' => 'A volcanic caldera safari destination.',
    ]);
});

test('place creation requires a valid country', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $response = $this->actingAs($adminUser)
        ->from(route('admin.destinations.places.create'))
        ->post(route('admin.destinations.places.store'), [
            'country_id' => 999999,
            'name' => 'Invalid Place',
        ]);

    $response->assertRedirect(route('admin.destinations.places.create'));
    $response->assertSessionHasErrors(['country_id']);
});

test('admin users can update places', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $countryOne = Country::factory()->create(['name' => 'Kenya']);
    $countryTwo = Country::factory()->create(['name' => 'Tanzania']);
    $place = Place::factory()->create([
        'country_id' => $countryOne->id,
        'name' => 'Serengeti',
        'slug' => 'serengeti',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.destinations.places.edit', ['place' => $place->id]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/destinations/places/Edit')
            ->where('place.id', $place->id)
            ->where('place.name', 'Serengeti')
        );

    $response = $this->actingAs($adminUser)
        ->patch(route('admin.destinations.places.update', ['place' => $place->id]), [
            'country_id' => $countryTwo->id,
            'name' => 'Tarangire',
            'overview' => 'Famous for elephant herds.',
        ]);

    $response->assertRedirect(route('admin.destinations.places'));
    $response->assertSessionHas('status', 'Place updated.');

    $this->assertDatabaseHas('places', [
        'id' => $place->id,
        'country_id' => $countryTwo->id,
        'name' => 'Tarangire',
        'slug' => 'tarangire',
    ]);
});

test('admin users can delete places', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $place = Place::factory()->create();

    $response = $this->actingAs($adminUser)
        ->delete(route('admin.destinations.places.destroy', ['place' => $place->id]));

    $response->assertRedirect(route('admin.destinations.places'));
    $response->assertSessionHas('status', 'Place deleted.');
    $this->assertModelMissing($place);
});

test('non admin users cannot modify places', function () {
    $user = User::factory()->create([
        'account_type' => 'customer',
    ]);
    $place = Place::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.destinations.places'))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('admin.destinations.places.store'), [
            'country_id' => $place->country_id,
            'name' => 'Blocked Place',
        ])
        ->assertForbidden();

    $this->actingAs($user)
        ->patch(route('admin.destinations.places.update', ['place' => $place->id]), [
            'country_id' => $place->country_id,
            'name' => 'Blocked Update',
        ])
        ->assertForbidden();

    $this->actingAs($user)
        ->delete(route('admin.destinations.places.destroy', ['place' => $place->id]))
        ->assertForbidden();
});
