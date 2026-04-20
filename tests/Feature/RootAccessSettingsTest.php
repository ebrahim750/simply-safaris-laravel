<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests are redirected away from the root access site settings page', function () {
    $this->get(route('root-access.site-settings.general'))
        ->assertRedirect(route('login'));
});

test('non root users cannot view the root access site settings page', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->get(route('root-access.site-settings.general'))
        ->assertForbidden();
});

test('root users can view the root access site settings index', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $this->actingAs($rootUser)
        ->get(route('root-access.site-settings'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('root-access/SiteSettings'),
        );
});

test('root users can view the general settings page', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Example Site',
        'site_tagline' => 'Built for testing',
        'currency' => 'KES',
        'primary_color' => '#112233',
    ]);

    $this->actingAs($rootUser)
        ->get(route('root-access.site-settings.general'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('root-access/site-settings/General')
            ->where('appBranding.siteName', $siteSettings->site_name)
            ->where('siteSettings.site_name', $siteSettings->site_name)
            ->where('siteSettings.site_tagline', $siteSettings->site_tagline)
            ->where('siteSettings.currency', 'KES')
            ->where('currencyOptions.0.value', 'USD'),
        );
});

test('root users can view the logo settings page', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    SiteSetting::query()->create([
        'site_name' => 'Example Site',
        'logo_path' => 'logos/test.png',
    ]);

    $this->actingAs($rootUser)
        ->get(route('root-access.site-settings.logo'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('root-access/site-settings/Logo')
            ->where('appBranding.siteName', 'Example Site')
            ->where('siteSettings.logo_path', 'logos/test.png'),
        );
});

test('root users can view the colors settings page', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    SiteSetting::query()->create([
        'site_name' => 'Example Site',
        'primary_color' => '#112233',
        'secondary_color' => '#445566',
        'accent_color' => '#778899',
    ]);

    $this->actingAs($rootUser)
        ->get(route('root-access.site-settings.colors'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('root-access/site-settings/Colors')
            ->where('appBranding.siteName', 'Example Site')
            ->where('siteSettings.primary_color', '#112233')
            ->where('siteSettings.secondary_color', '#445566')
            ->where('siteSettings.accent_color', '#778899'),
        );
});

test('root users can update general site settings', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Old Site',
    ]);

    $response = $this->actingAs($rootUser)
        ->put(route('root-access.site-settings.general.update'), [
            'site_name' => 'New Site',
            'site_tagline' => 'New tagline',
            'currency' => 'EUR',
        ]);

    $response->assertRedirect(route('root-access.site-settings.general'));
    $response->assertSessionHas('status', 'General settings updated.');

    expect($siteSettings->refresh()->site_name)->toBe('New Site');
    expect($siteSettings->site_tagline)->toBe('New tagline');
    expect($siteSettings->currency)->toBe('EUR');
});

test('root users can update color settings', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Color Site',
    ]);

    $response = $this->actingAs($rootUser)
        ->put(route('root-access.site-settings.colors.update'), [
            'primary_color' => '#112233',
            'secondary_color' => '#445566',
            'accent_color' => '#778899',
        ]);

    $response->assertRedirect(route('root-access.site-settings.colors'));
    $response->assertSessionHas('status', 'Color settings updated.');

    expect($siteSettings->refresh()->primary_color)->toBe('#112233');
    expect($siteSettings->secondary_color)->toBe('#445566');
    expect($siteSettings->accent_color)->toBe('#778899');
});

test('root users can upload logo assets', function () {
    Storage::fake('public');

    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    SiteSetting::query()->create([
        'site_name' => 'Upload Site',
    ]);

    $response = $this->actingAs($rootUser)
        ->put(route('root-access.site-settings.logo.update'), [
            'logo' => UploadedFile::fake()->image('logo.png'),
            'admin_logo_light' => UploadedFile::fake()->image('admin-light.png'),
            'admin_logo_dark' => UploadedFile::fake()->image('admin-dark.png'),
            'favicon' => UploadedFile::fake()->image('favicon.png'),
        ], ['Content-Type' => 'multipart/form-data']);

    $response->assertRedirect(route('root-access.site-settings.logo'));

    $siteSettings = SiteSetting::query()->first();

    expect($siteSettings->logo_path)->not->toBeNull();
    expect($siteSettings->admin_logo_path_light)->not->toBeNull();
    expect($siteSettings->admin_logo_path_dark)->not->toBeNull();
    expect($siteSettings->favicon_path)->not->toBeNull();

    Storage::disk('public')->assertExists($siteSettings->logo_path);
    Storage::disk('public')->assertExists($siteSettings->admin_logo_path_light);
    Storage::disk('public')->assertExists($siteSettings->admin_logo_path_dark);
    Storage::disk('public')->assertExists($siteSettings->favicon_path);
});

test('uploaded favicon is reflected in the app shell', function () {
    Storage::fake('public');

    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Favicon Site',
        'favicon_path' => 'site-settings/favicon.png',
    ]);

    Storage::disk('public')->put($siteSettings->favicon_path, 'favicon');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($siteSettings->favicon_path), false);
});

test('site name is reflected in the app shell title', function () {
    SiteSetting::query()->create([
        'site_name' => 'Simply Safaris HQ',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Simply Safaris HQ</title>', false);
});

test('uploaded admin logo is reflected on the login page', function () {
    Storage::fake('public');

    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Login Brand',
        'admin_logo_path_light' => 'site-settings/admin-light.png',
    ]);

    Storage::disk('public')->put($siteSettings->admin_logo_path_light, 'logo');

    $this->get(route('login'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($siteSettings->admin_logo_path_light), false);
});

test('single uploaded admin dark logo is still reflected on the login page', function () {
    Storage::fake('public');

    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Login Brand',
        'admin_logo_path_dark' => 'site-settings/admin-dark.png',
    ]);

    Storage::disk('public')->put($siteSettings->admin_logo_path_dark, 'logo');

    $this->get(route('login'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($siteSettings->admin_logo_path_dark), false);
});

test('blank site name falls back to the app name', function () {
    SiteSetting::query()->create([
        'site_name' => '   ',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Simply Safaris</title>', false)
        ->assertInertia(fn (Assert $page) => $page
            ->where('appBranding.siteName', 'Simply Safaris')
        );
});

test('non root users cannot update general site settings', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->put(route('root-access.site-settings.general.update'), [
            'site_name' => 'Blocked',
        ])
        ->assertForbidden();
});

test('non root users cannot update logo settings', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->put(route('root-access.site-settings.logo.update'), [
            'logo' => UploadedFile::fake()->image('logo.png'),
        ], ['Content-Type' => 'multipart/form-data'])
        ->assertForbidden();
});

test('non root users cannot update color settings', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->put(route('root-access.site-settings.colors.update'), [
            'primary_color' => '#112233',
        ])
        ->assertForbidden();
});
