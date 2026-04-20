<?php

use App\Mail\ProdTestEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('guests are redirected away from the prod test page', function () {
    $this->get(route('root-access.prod-test'))
        ->assertRedirect(route('login'));
});

test('non root users cannot view the prod test page', function () {
    $admin = User::factory()->create([
        'account_type' => 'admin',
    ]);

    $this->actingAs($admin)
        ->get(route('root-access.prod-test'))
        ->assertForbidden();
});

test('root users can view the prod test page', function () {
    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $this->actingAs($rootUser)
        ->get(route('root-access.prod-test'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('root-access/ProdTest')
            ->where('defaultReceiverEmail', 'projects@simplyit.co.tz'),
        );
});

test('root users can queue a prod test email', function () {
    Mail::fake();

    $rootUser = User::factory()->create([
        'account_type' => 'root',
    ]);

    $response = $this->actingAs($rootUser)
        ->from(route('root-access.prod-test'))
        ->post(route('root-access.prod-test.email-test.store'), [
            'receiver_email' => 'recipient@example.com',
        ]);

    $response->assertRedirect(route('root-access.prod-test'));

    Mail::assertQueued(ProdTestEmail::class, function (ProdTestEmail $mail) {
        return $mail->hasTo('recipient@example.com')
            && $mail->receiverEmail === 'recipient@example.com';
    });
});
