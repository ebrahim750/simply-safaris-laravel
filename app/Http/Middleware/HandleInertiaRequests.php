<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $faviconUrl = null;
        $siteSettings = Schema::hasTable('site_settings')
            ? SiteSetting::query()->first()
            : null;
        $siteName = $siteSettings?->site_name ?: config('app.name');
        $adminLogoLightUrl = null;
        $adminLogoDarkUrl = null;

        if ($siteSettings?->favicon_path) {
            $faviconUrl = Storage::disk('public')->url($siteSettings->favicon_path);
        }

        if ($siteSettings?->admin_logo_path_light) {
            $adminLogoLightUrl = Storage::disk('public')->url($siteSettings->admin_logo_path_light);
        }

        if ($siteSettings?->admin_logo_path_dark) {
            $adminLogoDarkUrl = Storage::disk('public')->url($siteSettings->admin_logo_path_dark);
        }

        View::share('faviconUrl', $faviconUrl);

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'status' => $request->session()->get('status'),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'applyUserChosenTheme' => ! ($request->is('/') || $request->routeIs('home')),
            'faviconUrl' => $faviconUrl,
            'siteSettings' => [
                'siteName' => $siteName,
                'adminLogoLightUrl' => $adminLogoLightUrl,
                'adminLogoDarkUrl' => $adminLogoDarkUrl,
            ],
        ];
    }
}
