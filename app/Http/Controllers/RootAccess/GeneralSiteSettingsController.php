<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\RootAccess\UpdateGeneralSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class GeneralSiteSettingsController extends Controller
{
    public function index(): Response
    {
        $siteSettings = $this->resolveSiteSettings();

        return Inertia::render('root-access/site-settings/General', [
            'siteSettings' => [
                'site_name' => $siteSettings->site_name,
                'site_tagline' => $siteSettings->site_tagline,
            ],
        ]);
    }

    public function update(UpdateGeneralSiteSettingsRequest $request): RedirectResponse
    {
        $siteSettings = $this->resolveSiteSettings();
        $siteSettings->fill($request->validated());
        $siteSettings->save();

        return to_route('root-access.site-settings.general')
            ->with('status', 'General settings updated.');
    }

    private function resolveSiteSettings(): SiteSetting
    {
        return SiteSetting::query()->firstOrCreate([], [
            'site_name' => config('app.name'),
        ]);
    }
}
