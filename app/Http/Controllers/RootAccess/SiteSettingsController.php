<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\RootAccess\UpdateSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingsController extends Controller
{
    /**
     * Display the site settings page.
     */
    public function index(): Response
    {
        $siteSettings = $this->resolveSiteSettings();

        return Inertia::render('root-access/SiteSettings', [
            'siteSettings' => [
                'site_name' => $siteSettings->site_name,
                'site_tagline' => $siteSettings->site_tagline,
                'primary_color' => $siteSettings->primary_color,
                'secondary_color' => $siteSettings->secondary_color,
                'accent_color' => $siteSettings->accent_color,
                'logo_path' => $siteSettings->logo_path,
                'admin_logo_path_light' => $siteSettings->admin_logo_path_light,
                'admin_logo_path_dark' => $siteSettings->admin_logo_path_dark,
                'favicon_path' => $siteSettings->favicon_path,
                'logo_url' => $siteSettings->logo_path ? Storage::disk('public')->url($siteSettings->logo_path) : null,
                'admin_logo_light_url' => $siteSettings->admin_logo_path_light ? Storage::disk('public')->url($siteSettings->admin_logo_path_light) : null,
                'admin_logo_dark_url' => $siteSettings->admin_logo_path_dark ? Storage::disk('public')->url($siteSettings->admin_logo_path_dark) : null,
                'favicon_url' => $siteSettings->favicon_path ? Storage::disk('public')->url($siteSettings->favicon_path) : null,
            ],
        ]);
    }

    /**
     * Update the site settings.
     */
    public function update(UpdateSiteSettingsRequest $request): RedirectResponse
    {
        $siteSettings = $this->resolveSiteSettings();
        $validated = $request->validated();

        $siteSettings->fill(Arr::except($validated, [
            'logo',
            'admin_logo_light',
            'admin_logo_dark',
            'favicon',
        ]));

        if ($request->hasFile('logo')) {
            $siteSettings->logo_path = $request->file('logo')->store('site-settings', 'public');
        }

        if ($request->hasFile('admin_logo_light')) {
            $siteSettings->admin_logo_path_light = $request->file('admin_logo_light')->store('site-settings', 'public');
        }

        if ($request->hasFile('admin_logo_dark')) {
            $siteSettings->admin_logo_path_dark = $request->file('admin_logo_dark')->store('site-settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $siteSettings->favicon_path = $request->file('favicon')->store('site-settings', 'public');
        }

        $siteSettings->save();

        return to_route('root-access.site-settings')
            ->with('status', 'Site settings updated.');
    }

    private function resolveSiteSettings(): SiteSetting
    {
        return SiteSetting::query()->firstOrCreate([], [
            'site_name' => config('app.name'),
        ]);
    }
}
