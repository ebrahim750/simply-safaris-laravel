<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForcePasswordChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ForcePasswordChangeController extends Controller
{
    /**
     * Show the forced password change screen.
     */
    public function edit(Request $request): RedirectResponse|Response
    {
        if (! $request->user()?->force_password_change) {
            return to_route('dashboard');
        }

        return Inertia::render('auth/ForcePasswordChange');
    }

    /**
     * Update the authenticated user's password.
     */
    public function update(ForcePasswordChangeRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->password = $request->string('password')->toString();
        $user->force_password_change = false;
        $user->save();

        return to_route('dashboard')->with('status', 'Password updated successfully.');
    }
}
