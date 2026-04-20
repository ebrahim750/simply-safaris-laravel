<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard receives site settings branding data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    SiteSetting::query()->create([
        'site_name' => 'Simply Safaris HQ',
        'admin_logo_path_light' => 'site-settings/admin-light.png',
        'admin_logo_path_dark' => 'site-settings/admin-dark.png',
    ]);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('siteSettings.siteName', 'Simply Safaris HQ')
            ->where('siteSettings.adminLogoLightUrl', Storage::disk('public')->url('site-settings/admin-light.png'))
            ->where('siteSettings.adminLogoDarkUrl', Storage::disk('public')->url('site-settings/admin-dark.png'))
        );
});
