<?php

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::forget('countries.catalog.v1');
});

test('admin users can view the countries page', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $country = Country::factory()->create([
        'name' => 'Kenya',
        'slug' => 'kenya',
        'country_code' => 'ke',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.destinations.countries'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/destinations/countries/Countries')
            ->has('countries', 1)
            ->where('countries.0.id', $country->id)
            ->where('countries.0.name', 'Kenya')
            ->where('countries.0.country_code', 'ke')
        );
});

test('admin users can open create country page and store a country', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.destinations.countries.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/destinations/countries/Create')
            ->has('country_options')
            ->where(
                'country_options',
                fn ($options) => collect($options)->contains(
                    fn ($option) => $option['name'] === 'Kenya' && $option['code'] === 'ke'
                )
            )
        );

    $response = $this->actingAs($adminUser)
        ->post(route('admin.destinations.countries.store'), [
            'name' => 'Tanzania',
            'overview' => 'A beautiful country in East Africa.',
            'best_time_to_go' => 'June to October',
            'high_season' => 'July to September',
            'electrical' => '230V, Type D/G plugs',
            'tourism_link' => 'https://www.tanzaniatourism.go.tz',
        ]);

    $response->assertRedirect(route('admin.destinations.countries'));
    $response->assertSessionHas('status', 'Country created.');

    $this->assertDatabaseHas('countries', [
        'name' => 'Tanzania',
        'slug' => 'tanzania',
        'country_code' => 'tz',
        'overview' => 'A beautiful country in East Africa.',
        'best_time_to_go' => 'June to October',
        'high_season' => 'July to September',
        'tourism_link' => 'https://www.tanzaniatourism.go.tz',
    ]);
});

test('country create generates a unique slug with suffix when needed', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    Country::factory()->create([
        'name' => 'Rwanda Existing',
        'slug' => 'rwanda',
    ]);

    $response = $this->actingAs($adminUser)
        ->post(route('admin.destinations.countries.store'), [
            'name' => 'Rwanda',
        ]);

    $response->assertRedirect(route('admin.destinations.countries'));

    $this->assertDatabaseHas('countries', [
        'name' => 'Rwanda',
        'slug' => 'rwanda-2',
    ]);
});

test('admin users can open edit page and update a country details', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $country = Country::factory()->create([
        'name' => 'Kenya',
        'slug' => 'kenya',
        'country_code' => 'ke',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.destinations.countries.edit', ['country' => $country->id]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/destinations/countries/Edit')
            ->where('country.id', $country->id)
            ->where('country.name', 'Kenya')
        );

    $response = $this->actingAs($adminUser)
        ->patch(route('admin.destinations.countries.update', ['country' => $country->id]), [
            'overview' => 'The Pearl of Africa.',
            'best_time_to_go' => 'December to February',
            'high_season' => 'June to August',
            'electrical' => '240V, Type G plugs',
            'tourism_link' => 'https://www.visituganda.com',
        ]);

    $response->assertRedirect(route('admin.destinations.countries.edit', $country));
    $response->assertSessionHas('status', 'Country updated.');

    $this->assertDatabaseHas('countries', [
        'id' => $country->id,
        'name' => 'Kenya',
        'slug' => 'kenya',
        'country_code' => 'ke',
        'overview' => 'The Pearl of Africa.',
        'best_time_to_go' => 'December to February',
        'high_season' => 'June to August',
        'tourism_link' => 'https://www.visituganda.com',
    ]);
});

test('admin users can delete countries', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $country = Country::factory()->create();

    $response = $this->actingAs($adminUser)
        ->delete(route('admin.destinations.countries.destroy', ['country' => $country->id]));

    $response->assertRedirect(route('admin.destinations.countries'));
    $response->assertSessionHas('status', 'Country deleted.');
    $this->assertModelMissing($country);
});

test('non admin users cannot access or modify countries routes', function () {
    $user = User::factory()->create([
        'account_type' => 'customer',
    ]);
    $country = Country::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.destinations.countries'))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('admin.destinations.countries.store'), [
            'name' => 'Blocked Country',
        ])
        ->assertForbidden();

    $this->actingAs($user)
        ->patch(route('admin.destinations.countries.update', ['country' => $country->id]), [
            'name' => 'Blocked Update',
        ])
        ->assertForbidden();

    $this->actingAs($user)
        ->delete(route('admin.destinations.countries.destroy', ['country' => $country->id]))
        ->assertForbidden();
});
