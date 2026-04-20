<?php

use App\Mail\ResetPasswordLinkEmail;
use App\Mail\UserCredentialsEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            ->where('users.0.can_delete', true)
            ->where('users.0.force_password_change', false)
            ->where('users.0.created_at_display', $listedUser->created_at->format('M j, Y g:i A'))
            ->where('users.1.email', $rootUser->email)
            ->where('users.1.account_type', $rootUser->account_type)
            ->where('users.1.can_delete', false)
            ->where('users.1.force_password_change', false)
            ->where('users.1.created_at_display', $rootUser->created_at->format('M j, Y g:i A')),
        );
});

test('root users can create users from root access', function () {
    Mail::fake();

    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $response = $this->actingAs($rootUser)
        ->from(route('root-access.users'))
        ->post(route('root-access.users.store'), [
            'name' => 'Created User',
            'email' => 'created@example.com',
            'account_type' => 'admin',
            'send_credentials_email' => '0',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response->assertRedirect(route('root-access.users'));
    $response->assertSessionHas('status', 'Created user created@example.com.');

    $this->assertDatabaseHas('users', [
        'name' => 'Created User',
        'email' => 'created@example.com',
        'account_type' => 'admin',
        'force_password_change' => true,
    ]);

    Mail::assertNothingQueued();
});

test('root users can queue credentials email when creating a user', function () {
    Mail::fake();

    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $response = $this->actingAs($rootUser)
        ->from(route('root-access.users'))
        ->post(route('root-access.users.store'), [
            'name' => 'Credentials User',
            'email' => 'credentials@example.com',
            'account_type' => 'admin',
            'send_credentials_email' => '1',
            'password' => 'generated-password',
            'password_confirmation' => 'generated-password',
        ]);

    $response->assertRedirect(route('root-access.users'));
    $response->assertSessionHas('status', 'Created user credentials@example.com.');

    Mail::assertQueued(UserCredentialsEmail::class, function (UserCredentialsEmail $mail) {
        return $mail->hasTo('credentials@example.com')
            && $mail->recipientEmail === 'credentials@example.com'
            && $mail->plainTextPassword === 'generated-password'
            && $mail->accountType === 'admin';
    });
});

test('root users can update other users from root access', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $editableUser = User::factory()->create([
        'name' => 'Before Name',
        'email' => 'before@example.com',
        'account_type' => 'admin',
    ]);

    $response = $this->actingAs($rootUser)
        ->patch(route('root-access.users.update', $editableUser), [
            'name' => 'After Name',
            'email' => 'after@example.com',
            'account_type' => 'root',
        ]);

    $response->assertRedirect(route('root-access.users'));
    $response->assertSessionHas('status', 'Updated user after@example.com.');

    $editableUser->refresh();

    expect($editableUser->name)->toBe('After Name');
    expect($editableUser->email)->toBe('after@example.com');
    expect($editableUser->account_type)->toBe('root');
});

test('root users can update another users password from root access', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $editableUser = User::factory()->create([
        'force_password_change' => false,
    ]);

    $response = $this->actingAs($rootUser)
        ->put(route('root-access.users.password.update', $editableUser), [
            'password' => 'fresh-password',
            'password_confirmation' => 'fresh-password',
        ]);

    $response->assertRedirect(route('root-access.users'));
    $response->assertSessionHas('status', "Updated password for {$editableUser->email}.");

    expect(Hash::check('fresh-password', $editableUser->refresh()->password))->toBeTrue()
        ->and($editableUser->force_password_change)->toBeTrue();
});

test('root users can send a password reset link from root access', function () {
    Mail::fake();

    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $editableUser = User::factory()->create();

    $response = $this->actingAs($rootUser)
        ->post(route('root-access.users.password-reset-link.store', $editableUser));

    $response->assertRedirect(route('root-access.users'));
    $response->assertSessionHas('status', "Sent password reset link to {$editableUser->email}.");

    Mail::assertQueued(ResetPasswordLinkEmail::class, function (ResetPasswordLinkEmail $mail) use ($editableUser) {
        return $mail->hasTo($editableUser->email)
            && $mail->recipientEmail === $editableUser->email
            && $mail->recipientName === $editableUser->name;
    });
});

test('root users can delete other users from root access', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);
    $deletableUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $response = $this->actingAs($rootUser)
        ->delete(route('root-access.users.destroy', $deletableUser));

    $response->assertRedirect(route('root-access.users'));
    $response->assertSessionHas('status', "Deleted user {$deletableUser->email}.");
    $this->assertModelMissing($deletableUser);
});

test('root users cannot delete themselves from root access', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $this->actingAs($rootUser)
        ->delete(route('root-access.users.destroy', $rootUser))
        ->assertForbidden();

    $this->assertModelExists($rootUser);
});

test('root users cannot update themselves from root access', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $this->actingAs($rootUser)
        ->patch(route('root-access.users.update', $rootUser), [
            'name' => 'Updated Root',
            'email' => 'updated-root@example.com',
            'account_type' => 'root',
        ])
        ->assertForbidden();
});

test('root users cannot update their own password from root access', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $this->actingAs($rootUser)
        ->put(route('root-access.users.password.update', $rootUser), [
            'password' => 'fresh-password',
            'password_confirmation' => 'fresh-password',
        ])
        ->assertForbidden();
});

test('root users cannot send themselves a password reset link from root access', function () {
    Mail::fake();

    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $this->actingAs($rootUser)
        ->post(route('root-access.users.password-reset-link.store', $rootUser))
        ->assertForbidden();

    Mail::assertNothingQueued();
});

test('non root users cannot delete users from root access', function () {
    $user = User::factory()->create([
        'account_type' => 'admin',
    ]);
    $anotherUser = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($user)
        ->delete(route('root-access.users.destroy', $anotherUser))
        ->assertForbidden();

    $this->assertModelExists($anotherUser);
});
