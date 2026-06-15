<?php

use App\Mail\TourEnquirySubmitted;
use App\Models\SiteSetting;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class)->group('tour-enquiry');

beforeEach(function (): void {
    SiteSetting::query()->firstOrCreate([], [
        'site_name' => config('app.name'),
        'currency' => 'USD',
    ]);
});

it('persists a tour enquiry and queues notification emails', function (): void {
    Mail::fake();
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true]),
    ]);

    SiteSetting::query()->first()->update([
        'form_emails' => 'admin@example.com, manager@example.com',
        'form_emails_enabled' => true,
    ]);

    $tour = Tour::factory()->create();

    $response = $this->post(route('tours.enquiry', $tour), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'booking_date' => now()->addWeek()->format('Y-m-d'),
        'adults' => '2',
        'children' => '1',
        'message' => 'Looking forward to this tour.',
        'cf-turnstile-response' => 'test-token',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('tour_enquiries', [
        'tour_id' => $tour->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'adults' => '2',
        'children' => '1',
    ]);

    Mail::assertQueued(TourEnquirySubmitted::class, function (TourEnquirySubmitted $mail): bool {
        return $mail->hasTo('admin@example.com')
            && $mail->hasTo('manager@example.com');
    });
});

it('does not queue emails when form emails are disabled', function (): void {
    Mail::fake();
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true]),
    ]);

    SiteSetting::query()->first()->update([
        'form_emails' => 'admin@example.com',
        'form_emails_enabled' => false,
    ]);

    $tour = Tour::factory()->create();

    $this->post(route('tours.enquiry', $tour), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '+0987654321',
        'booking_date' => now()->addWeek()->format('Y-m-d'),
        'adults' => '1',
        'cf-turnstile-response' => 'test-token',
    ])
        ->assertRedirect()
        ->assertSessionHas('status');

    Mail::assertNothingQueued();
});

it('does not queue emails for invalid addresses', function (): void {
    Mail::fake();
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true]),
    ]);

    SiteSetting::query()->first()->update([
        'form_emails' => 'not-an-email',
        'form_emails_enabled' => true,
    ]);

    $tour = Tour::factory()->create();

    $this->post(route('tours.enquiry', $tour), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '+0987654321',
        'booking_date' => now()->addWeek()->format('Y-m-d'),
        'adults' => '1',
        'cf-turnstile-response' => 'test-token',
    ])
        ->assertRedirect()
        ->assertSessionHas('status');

    Mail::assertNothingQueued();
});
