<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmitted;
use App\Models\ContactSubmission;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function contact(Request $request): Response
    {
        return Inertia::render('Contact', [
            'turnstileSiteKey' => config('services.cf_turnstile.site_key'),
            'isLocal' => app()->environment('local'),
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if (! app()->environment('local')) {
            $turnstileResponse = Http::timeout(5)->asForm()->post(
                'https://challenges.cloudflare.com/turnstile/v0/siteverify',
                [
                    'secret' => config('services.cf_turnstile.secret'),
                    'response' => $request->input('cf-turnstile-response'),
                    'remoteip' => $request->ip(),
                ]
            );

            if (! $turnstileResponse->json('success')) {
                throw ValidationException::withMessages([
                    'cf-turnstile-response' => ['Please complete the security challenge.'],
                ]);
            }
        }

        $contactSubmission = ContactSubmission::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $siteSettings = SiteSetting::query()->first();
        $formEmailsEnabled = $siteSettings?->form_emails_enabled ?? false;
        $formEmails = $siteSettings?->form_emails;

        if ($formEmailsEnabled && is_string($formEmails) && $formEmails !== '') {
            $recipients = collect(explode(',', $formEmails))
                ->map(fn (string $email): string => trim($email))
                ->filter(fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
                ->values()
                ->all();

            if (count($recipients) > 0) {
                Mail::to($recipients)->queue(new ContactSubmitted($contactSubmission));
            }
        }

        return redirect()->back()->with('status', 'Your message has been sent. We will be in touch soon.');
    }
}
