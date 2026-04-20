<?php

use App\Http\Controllers\RootAccess\ProdTestController;
use App\Http\Controllers\RootAccess\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::middleware('root')->group(function () {
        Route::get('root-access/prod-test', [ProdTestController::class, 'index'])->name('root-access.prod-test');
        Route::post('root-access/prod-test/email-test', [ProdTestController::class, 'sendEmailTest'])->name('root-access.prod-test.email-test.store');
        Route::get('root-access/users', [UserController::class, 'index'])->name('root-access.users');
        Route::post('root-access/users', [UserController::class, 'store'])->name('root-access.users.store');
        Route::delete('root-access/users/{user}', [UserController::class, 'destroy'])->name('root-access.users.destroy');
    });
});

require __DIR__.'/settings.php';
