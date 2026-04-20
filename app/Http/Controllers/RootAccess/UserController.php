<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\RootAccess\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display the root access users page.
     */
    public function index(): Response
    {
        return Inertia::render('root-access/Users', [
            'users' => User::query()
                ->latest('id')
                ->get(['id', 'name', 'email', 'account_type', 'created_at'])
                ->map(fn (User $user): array => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account_type' => $user->account_type,
                    'created_at' => $user->created_at?->toIso8601String(),
                    'created_at_display' => $user->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return to_route('root-access.users');
    }
}
