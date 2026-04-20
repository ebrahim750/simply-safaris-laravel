<?php

use App\Mail\ResetPasswordLinkEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Features;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::resetPasswords());
});

test('reset password link screen can be rendered', function () {
    $response = $this->get(route('password.request'));

    $response->assertOk();
});

test('reset password link can be requested', function () {
    Mail::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Mail::assertQueued(ResetPasswordLinkEmail::class, function (ResetPasswordLinkEmail $mail) use ($user) {
        return $mail->hasTo($user->email)
            && $mail->recipientEmail === $user->email
            && $mail->recipientName === $user->name;
    });
});

test('reset password screen can be rendered', function () {
    Mail::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Mail::assertQueued(ResetPasswordLinkEmail::class, function (ResetPasswordLinkEmail $mail) {
        preg_match('#/reset-password/([^?]+)#', $mail->resetUrl, $matches);
        $token = $matches[1] ?? null;

        $response = $this->get(route('password.reset', $token));

        $response->assertOk();

        return true;
    });
});

test('password can be reset with valid token', function () {
    Mail::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Mail::assertQueued(ResetPasswordLinkEmail::class, function (ResetPasswordLinkEmail $mail) use ($user) {
        preg_match('#/reset-password/([^?]+)#', $mail->resetUrl, $matches);
        $token = $matches[1] ?? null;

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});

test('password reset notification is queued markdown mail', function () {
    $user = User::factory()->create();
    $mail = new ResetPasswordLinkEmail(
        recipientName: $user->name,
        recipientEmail: $user->email,
        resetUrl: route('password.reset', [
            'token' => 'test-token',
            'email' => $user->email,
        ]),
        expireMinutes: 60,
    );

    expect($mail)->toBeInstanceOf(ShouldQueue::class);
    expect($mail->content()->markdown)->toBe('emails.password-reset-link');
    expect($mail->resetUrl)->toContain('reset-password/test-token');
});

test('password cannot be reset with invalid token', function () {
    $user = User::factory()->create();

    $response = $this->post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('email');
});
