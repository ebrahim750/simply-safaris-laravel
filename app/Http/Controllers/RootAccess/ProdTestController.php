<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\RootAccess\SendProdTestEmailRequest;
use App\Mail\ProdTestEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ProdTestController extends Controller
{
    /**
     * Display the production test page.
     */
    public function index(): Response
    {
        return Inertia::render('root-access/ProdTest', [
            'defaultReceiverEmail' => 'projects@simplyit.co.tz',
        ]);
    }

    /**
     * Queue a production test email.
     */
    public function sendEmailTest(SendProdTestEmailRequest $request): RedirectResponse
    {
        $receiverEmail = $request->validated('receiver_email');

        Mail::to($receiverEmail)->queue(new ProdTestEmail(
            receiverEmail: $receiverEmail,
            triggeredByName: $request->user()->name,
        ));

        return to_route('root-access.prod-test')->with(
            'status',
            "Queued a test email for {$receiverEmail}."
        );
    }
}
