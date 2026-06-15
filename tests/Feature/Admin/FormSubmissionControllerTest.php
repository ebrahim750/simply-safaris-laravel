<?php

use App\Models\Tour;
use App\Models\TourEnquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('admin users can view the contact form submissions page', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.form-submissions.contact'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/form-submissions/Contact')
        );
});

test('admin users can view the tour form submissions page', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tour = Tour::factory()->create();
    $enquiry = TourEnquiry::factory()->create([
        'tour_id' => $tour->id,
        'name' => 'Jane Doe',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.form-submissions.tour'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/form-submissions/Tour')
            ->has('enquiries.data', 1)
            ->where('enquiries.data.0.id', $enquiry->id)
            ->where('enquiries.data.0.name', 'Jane Doe')
            ->where('enquiries.data.0.tour.name', $tour->name)
        );
});

test('admin users can search tour form submissions', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tourA = Tour::factory()->create([
        'name' => 'Serengeti Safari',
    ]);
    $tourB = Tour::factory()->create([
        'name' => 'Ngorongoro Crater',
    ]);

    TourEnquiry::factory()->create([
        'tour_id' => $tourA->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '0701000000',
    ]);
    TourEnquiry::factory()->create([
        'tour_id' => $tourB->id,
        'name' => 'Jane Smith',
        'email' => 'jane@test.com',
        'phone' => '0702000000',
    ]);

    $this->actingAs($adminUser)
        ->get(route('admin.form-submissions.tour', ['search' => 'Jane']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/form-submissions/Tour')
            ->has('enquiries.data', 1)
            ->where('enquiries.data.0.name', 'Jane Smith')
        );

    $this->actingAs($adminUser)
        ->get(route('admin.form-submissions.tour', ['search' => 'Serengeti']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('enquiries.data', 1)
            ->where('enquiries.data.0.tour.name', 'Serengeti Safari')
        );

    $this->actingAs($adminUser)
        ->get(route('admin.form-submissions.tour', ['search' => '0702000000']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('enquiries.data', 1)
            ->where('enquiries.data.0.phone', '0702000000')
        );
});

test('admin users can delete a tour enquiry', function () {
    $adminUser = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $tour = Tour::factory()->create();
    $enquiry = TourEnquiry::factory()->create([
        'tour_id' => $tour->id,
    ]);

    $this->actingAs($adminUser)
        ->delete(route('admin.form-submissions.tour.destroy', $enquiry))
        ->assertRedirect(route('admin.form-submissions.tour'))
        ->assertSessionHas('status');

    $this->assertDatabaseMissing('tour_enquiries', [
        'id' => $enquiry->id,
    ]);
});

test('non-admin users cannot access form submissions pages', function () {
    $regularUser = User::factory()->create([
        'account_type' => 'user',
    ]);
    $enquiry = TourEnquiry::factory()->create();

    $this->actingAs($regularUser)
        ->get(route('admin.form-submissions.contact'))
        ->assertForbidden();

    $this->actingAs($regularUser)
        ->get(route('admin.form-submissions.tour'))
        ->assertForbidden();

    $this->actingAs($regularUser)
        ->delete(route('admin.form-submissions.tour.destroy', $enquiry))
        ->assertForbidden();
});
