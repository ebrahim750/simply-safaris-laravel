<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('users with force password change enabled are redirected after login', function () {
    Http::fake(['https://challenges.cloudflare.com/turnstile/*' => Http::response(['success' => true])]);

    $user = User::factory()->create([
        'force_password_change' => true,
    ]);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
        'cf-turnstile-response' => 'fake-token',
    ])->assertRedirect(route('force-password.edit', absolute: false));

    $this->assertAuthenticatedAs($user);
});

test('users with force password change enabled are redirected away from protected pages', function () {
    $user = User::factory()->create([
        'force_password_change' => true,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('force-password.edit'));
});

test('forced password change page is displayed', function () {
    $user = User::factory()->create([
        'force_password_change' => true,
    ]);

    $this->actingAs($user)
        ->get(route('force-password.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/ForcePasswordChange'),
        );
});

test('users can set a new password from the forced password change page', function () {
    $user = User::factory()->create([
        'force_password_change' => true,
    ]);

    $this->actingAs($user)
        ->put(route('force-password.update'), [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertRedirect(route('dashboard'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue()
        ->and($user->force_password_change)->toBeFalse();
});
