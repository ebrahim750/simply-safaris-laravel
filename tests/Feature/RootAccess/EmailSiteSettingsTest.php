<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->group('root-access');

beforeEach(function (): void {
    SiteSetting::query()->firstOrCreate([], [
        'site_name' => config('app.name'),
        'currency' => 'USD',
    ]);
});

it('renders the email settings page for root users', function (): void {
    $user = User::factory()->create(['account_type' => 'root']);

    $this->actingAs($user)
        ->get(route('root-access.site-settings.emails'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('root-access/site-settings/Emails')
            ->has('siteSettings.form_emails')
            ->has('siteSettings.form_emails_enabled')
        );
});

it('updates email settings for root users', function (): void {
    $user = User::factory()->create(['account_type' => 'root']);

    $this->actingAs($user)
        ->put(route('root-access.site-settings.emails.update'), [
            'form_emails' => 'admin@example.com, manager@example.com',
            'form_emails_enabled' => true,
        ])
        ->assertRedirect(route('root-access.site-settings.emails'))
        ->assertSessionHas('status');

    $settings = SiteSetting::query()->first();

    expect($settings->form_emails)->toBe('admin@example.com, manager@example.com')
        ->and($settings->form_emails_enabled)->toBeTrue();
});

it('rejects updates from non-root users', function (): void {
    $user = User::factory()->create(['account_type' => 'admin']);

    $this->actingAs($user)
        ->put(route('root-access.site-settings.emails.update'), [
            'form_emails' => 'admin@example.com',
            'form_emails_enabled' => true,
        ])
        ->assertForbidden();
});
