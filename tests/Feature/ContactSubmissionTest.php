<?php

use App\Mail\ContactSubmitted;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class)->group('contact-submission');

beforeEach(function (): void {
    SiteSetting::query()->firstOrCreate([], [
        'site_name' => config('app.name'),
        'currency' => 'USD',
    ]);
});

it('persists a contact submission and queues notification emails', function (): void {
    Mail::fake();
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response(['success' => true]),
    ]);

    SiteSetting::query()->first()->update([
        'form_emails' => 'admin@example.com, manager@example.com',
        'form_emails_enabled' => true,
    ]);

    $response = $this->post(route('contact.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'message' => 'I would like to book a tour.',
        'cf-turnstile-response' => 'test-token',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('contact_submissions', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'message' => 'I would like to book a tour.',
    ]);

    Mail::assertQueued(ContactSubmitted::class, function (ContactSubmitted $mail): bool {
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

    $this->post(route('contact.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '+0987654321',
        'message' => 'Please call me back.',
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

    $this->post(route('contact.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '+0987654321',
        'message' => 'Please call me back.',
        'cf-turnstile-response' => 'test-token',
    ])
        ->assertRedirect()
        ->assertSessionHas('status');

    Mail::assertNothingQueued();
});
