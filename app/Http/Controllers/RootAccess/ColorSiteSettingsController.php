<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\RootAccess\UpdateColorSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ColorSiteSettingsController extends Controller
{
    public function index(): Response
    {
        $siteSettings = $this->resolveSiteSettings();

        return Inertia::render('root-access/site-settings/Colors', [
            'siteSettings' => [
                'primary_color' => $siteSettings->primary_color,
                'secondary_color' => $siteSettings->secondary_color,
                'accent_color' => $siteSettings->accent_color,
            ],
        ]);
    }

    public function update(UpdateColorSiteSettingsRequest $request): RedirectResponse
    {
        $siteSettings = $this->resolveSiteSettings();
        $siteSettings->fill($request->validated());
        $siteSettings->save();

        return to_route('root-access.site-settings.colors')
            ->with('status', 'Color settings updated.');
    }

    private function resolveSiteSettings(): SiteSetting
    {
        return SiteSetting::query()->firstOrCreate([], [
            'site_name' => config('app.name'),
        ]);
    }
}
