<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\RootAccess\UpdateLogoSiteSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class LogoSiteSettingsController extends Controller
{
    public function index(): Response
    {
        $siteSettings = $this->resolveSiteSettings();

        return Inertia::render('root-access/site-settings/Logo', [
            'siteSettings' => [
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

    public function update(UpdateLogoSiteSettingsRequest $request): RedirectResponse
    {
        $siteSettings = $this->resolveSiteSettings();
        $status = null;

        if ($request->hasFile('logo')) {
            $siteSettings->logo_path = $request->file('logo')->store('site-settings', 'public');
            $status = 'Logo uploaded.';
        }

        if ($request->hasFile('admin_logo_light')) {
            $siteSettings->admin_logo_path_light = $request->file('admin_logo_light')->store('site-settings', 'public');
            $status = 'Admin logo light uploaded.';
        }

        if ($request->hasFile('admin_logo_dark')) {
            $siteSettings->admin_logo_path_dark = $request->file('admin_logo_dark')->store('site-settings', 'public');
            $status = 'Admin logo dark uploaded.';
        }

        if ($request->hasFile('favicon')) {
            $siteSettings->favicon_path = $request->file('favicon')->store('site-settings', 'public');
            $status = 'Favicon uploaded.';
        }

        $siteSettings->save();

        return to_route('root-access.site-settings.logo')
            ->with('status', $status ?? 'Logo settings updated.');
    }

    public function remove(): RedirectResponse
    {
        if (request()->user()?->account_type !== 'root') {
            abort(403);
        }

        $siteSettings = $this->resolveSiteSettings();
        $field = request()->query('field');
        $status = null;

        if ($field === 'logo') {
            $this->deleteFile($siteSettings->logo_path);
            $siteSettings->logo_path = null;
            $status = 'Logo removed.';
        } elseif ($field === 'favicon') {
            $this->deleteFile($siteSettings->favicon_path);
            $siteSettings->favicon_path = null;
            $status = 'Favicon removed.';
        } elseif ($field === 'admin_logo_light') {
            $this->deleteFile($siteSettings->admin_logo_path_light);
            $siteSettings->admin_logo_path_light = null;
            $status = 'Admin logo light removed.';
        } elseif ($field === 'admin_logo_dark') {
            $this->deleteFile($siteSettings->admin_logo_path_dark);
            $siteSettings->admin_logo_path_dark = null;
            $status = 'Admin logo dark removed.';
        }

        $siteSettings->save();

        return to_route('root-access.site-settings.logo')
            ->with('status', $status ?? 'Logo settings updated.');
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function resolveSiteSettings(): SiteSetting
    {
        return SiteSetting::query()->firstOrCreate([], [
            'site_name' => config('app.name'),
        ]);
    }
}
