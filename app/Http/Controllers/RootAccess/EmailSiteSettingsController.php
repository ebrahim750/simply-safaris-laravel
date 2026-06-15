<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\RootAccess\UpdateEmailSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class EmailSiteSettingsController extends Controller
{
    public function index(): Response
    {
        $siteSettings = $this->resolveSiteSettings();

        return Inertia::render('root-access/site-settings/Emails', [
            'siteSettings' => [
                'form_emails' => $siteSettings->form_emails,
                'form_emails_enabled' => $siteSettings->form_emails_enabled,
            ],
        ]);
    }

    public function update(UpdateEmailSiteSettingsRequest $request): RedirectResponse
    {
        $siteSettings = $this->resolveSiteSettings();
        $siteSettings->fill($request->validated());
        $siteSettings->save();

        return to_route('root-access.site-settings.emails')
            ->with('status', 'Email settings updated.');
    }

    private function resolveSiteSettings(): SiteSetting
    {
        return SiteSetting::query()->firstOrCreate([], [
            'site_name' => config('app.name'),
            'currency' => 'USD',
        ]);
    }
}
