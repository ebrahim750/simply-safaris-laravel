<?php

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests are redirected away from the root access site settings page', function () {
    $this->get(route('root-access.site-settings'))
        ->assertRedirect(route('login'));
});

test('non root users cannot view the root access site settings page', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->get(route('root-access.site-settings'))
        ->assertForbidden();
});

test('root users can view the root access site settings page', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Example Site',
        'site_tagline' => 'Built for testing',
        'primary_color' => '#112233',
    ]);

    $this->actingAs($rootUser)
        ->get(route('root-access.site-settings'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('root-access/SiteSettings')
            ->where('siteSettings.site_name', $siteSettings->site_name)
            ->where('siteSettings.site_tagline', $siteSettings->site_tagline)
            ->where('siteSettings.primary_color', $siteSettings->primary_color),
        );
});

test('site settings singleton row is created automatically when missing', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    expect(SiteSetting::query()->count())->toBe(0);

    $this->actingAs($rootUser)
        ->get(route('root-access.site-settings'))
        ->assertOk();

    expect(SiteSetting::query()->count())->toBe(1);
    expect(SiteSetting::query()->first()?->site_name)->toBe(config('app.name'));
});

test('root users can update site setting text and color fields', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Old Site',
    ]);

    $response = $this->actingAs($rootUser)
        ->put(route('root-access.site-settings.update'), [
            'site_name' => 'New Site',
            'site_tagline' => 'New tagline',
            'primary_color' => '#112233',
            'secondary_color' => '#445566',
            'accent_color' => '#778899',
        ]);

    $response->assertRedirect(route('root-access.site-settings'));
    $response->assertSessionHas('status', 'Site settings updated.');

    expect($siteSettings->refresh()->site_name)->toBe('New Site');
    expect($siteSettings->site_tagline)->toBe('New tagline');
    expect($siteSettings->primary_color)->toBe('#112233');
    expect($siteSettings->secondary_color)->toBe('#445566');
    expect($siteSettings->accent_color)->toBe('#778899');
});

test('root users can upload site setting assets', function () {
    Storage::fake('public');

    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $siteSettings = SiteSetting::query()->create([
        'site_name' => 'Upload Site',
    ]);

    $response = $this->actingAs($rootUser)
        ->put(route('root-access.site-settings.update'), [
            'site_name' => 'Upload Site',
            'site_tagline' => '',
            'primary_color' => '',
            'secondary_color' => '',
            'accent_color' => '',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'admin_logo_light' => UploadedFile::fake()->image('admin-light.png'),
            'admin_logo_dark' => UploadedFile::fake()->image('admin-dark.png'),
            'favicon' => UploadedFile::fake()->image('favicon.png'),
        ]);

    $response->assertRedirect(route('root-access.site-settings'));

    $siteSettings->refresh();

    expect($siteSettings->logo_path)->not->toBeNull();
    expect($siteSettings->admin_logo_path_light)->not->toBeNull();
    expect($siteSettings->admin_logo_path_dark)->not->toBeNull();
    expect($siteSettings->favicon_path)->not->toBeNull();

    Storage::disk('public')->assertExists($siteSettings->logo_path);
    Storage::disk('public')->assertExists($siteSettings->admin_logo_path_light);
    Storage::disk('public')->assertExists($siteSettings->admin_logo_path_dark);
    Storage::disk('public')->assertExists($siteSettings->favicon_path);
});

test('non root users cannot update site settings', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->put(route('root-access.site-settings.update'), [
            'site_name' => 'Blocked',
        ])
        ->assertForbidden();
});
