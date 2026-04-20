<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests are redirected away from the root access users page', function () {
    $this->get(route('root-access.users'))
        ->assertRedirect(route('login'));
});

test('non root users cannot view the root access users page', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->get(route('root-access.users'))
        ->assertForbidden();
});

test('non root users cannot create users from root access', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->post(route('root-access.users.store'), [
            'name' => 'Blocked User',
            'email' => 'blocked@example.com',
            'account_type' => 'admin',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertForbidden();
});

test('root users can view the root access users page', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $listedUser = User::factory()->create([
        'name' => 'Jane Root',
        'email' => 'jane@example.com',
        'account_type' => 'admin',
    ]);

    $this->actingAs($rootUser)
        ->get(route('root-access.users'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('root-access/Users')
            ->has('users', 2)
            ->where('users.0.email', $listedUser->email)
            ->where('users.0.account_type', $listedUser->account_type)
            ->where('users.0.created_at_display', $listedUser->created_at->format('M j, Y g:i A'))
            ->where('users.1.email', $rootUser->email)
            ->where('users.1.account_type', $rootUser->account_type)
            ->where('users.1.created_at_display', $rootUser->created_at->format('M j, Y g:i A')),
        );
});

test('root users can create users from root access', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $response = $this->actingAs($rootUser)
        ->from(route('root-access.users'))
        ->post(route('root-access.users.store'), [
            'name' => 'Created User',
            'email' => 'created@example.com',
            'account_type' => 'admin',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response->assertRedirect(route('root-access.users'));

    $this->assertDatabaseHas('users', [
        'name' => 'Created User',
        'email' => 'created@example.com',
        'account_type' => 'admin',
    ]);
});
