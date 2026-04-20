<?php

use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\RootAccess\ColorSiteSettingsController;
use App\Http\Controllers\RootAccess\GeneralSiteSettingsController;
use App\Http\Controllers\RootAccess\LogoSiteSettingsController;
use App\Http\Controllers\RootAccess\ProdTestController;
use App\Http\Controllers\RootAccess\SiteSettingsController;
use App\Http\Controllers\RootAccess\UserController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('force-password', [ForcePasswordChangeController::class, 'edit'])->name('force-password.edit');
    Route::put('force-password', [ForcePasswordChangeController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('force-password.update');
});

Route::middleware(['auth', 'password.change.required', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::middleware('root')->group(function () {
        Route::get('root-access/prod-test', [ProdTestController::class, 'index'])->name('root-access.prod-test');
        Route::post('root-access/prod-test/email-test', [ProdTestController::class, 'sendEmailTest'])->name('root-access.prod-test.email-test.store');
        Route::get('root-access/site-settings', [SiteSettingsController::class, 'index'])->name('root-access.site-settings');
        Route::get('root-access/site-settings/general', [GeneralSiteSettingsController::class, 'index'])->name('root-access.site-settings.general');
        Route::put('root-access/site-settings/general', [GeneralSiteSettingsController::class, 'update'])->name('root-access.site-settings.general.update');
        Route::get('root-access/site-settings/logo', [LogoSiteSettingsController::class, 'index'])->name('root-access.site-settings.logo');
        Route::put('root-access/site-settings/logo', [LogoSiteSettingsController::class, 'update'])->name('root-access.site-settings.logo.update');
        Route::get('root-access/site-settings/colors', [ColorSiteSettingsController::class, 'index'])->name('root-access.site-settings.colors');
        Route::put('root-access/site-settings/colors', [ColorSiteSettingsController::class, 'update'])->name('root-access.site-settings.colors.update');
        Route::get('root-access/users', [UserController::class, 'index'])->name('root-access.users');
        Route::post('root-access/users', [UserController::class, 'store'])->name('root-access.users.store');
        Route::patch('root-access/users/{user}', [UserController::class, 'update'])->name('root-access.users.update');
        Route::put('root-access/users/{user}/password', [UserController::class, 'updatePassword'])->name('root-access.users.password.update');
        Route::post('root-access/users/{user}/password-reset-link', [UserController::class, 'sendPasswordResetLink'])->name('root-access.users.password-reset-link.store');
        Route::delete('root-access/users/{user}', [UserController::class, 'destroy'])->name('root-access.users.destroy');
    });
});

require __DIR__.'/settings.php';
