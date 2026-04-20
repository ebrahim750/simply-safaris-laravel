<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\RootAccess\StoreUserRequest;
use App\Mail\UserCredentialsEmail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display the root access users page.
     */
    public function index(Request $request): Response
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
                    'can_delete' => $request->user()?->id !== $user->id,
                ]),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $plainTextPassword = $validated['password'];

        $user = User::create(Arr::except($validated, ['send_credentials_email']));
        $user->force_password_change = true;
        $user->save();

        if ($request->boolean('send_credentials_email')) {
            Mail::to($user->email)->queue(new UserCredentialsEmail(
                recipientName: $user->name,
                recipientEmail: $user->email,
                accountType: $user->account_type,
                plainTextPassword: $plainTextPassword,
                triggeredByName: $request->user()->name,
            ));
        }

        return to_route('root-access.users')
            ->with('status', "Created user {$user->email}.");
    }

    /**
     * Remove the specified user.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()?->is($user), 403);

        $deletedUserEmail = $user->email;

        $user->delete();

        return to_route('root-access.users')
            ->with('status', "Deleted user {$deletedUserEmail}.");
    }
}
