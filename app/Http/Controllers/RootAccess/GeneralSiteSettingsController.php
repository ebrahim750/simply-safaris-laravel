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
    private const CURRENCY_OPTIONS = [
        ['value' => 'USD', 'label' => 'US Dollar (USD)'],
        ['value' => 'EUR', 'label' => 'Euro (EUR)'],
        ['value' => 'GBP', 'label' => 'British Pound (GBP)'],
        ['value' => 'KES', 'label' => 'Kenyan Shilling (KES)'],
        ['value' => 'TZS', 'label' => 'Tanzanian Shilling (TZS)'],
        ['value' => 'UGX', 'label' => 'Ugandan Shilling (UGX)'],
        ['value' => 'ZAR', 'label' => 'South African Rand (ZAR)'],
    ];

    public function index(): Response
    {
        $siteSettings = $this->resolveSiteSettings();

        return Inertia::render('root-access/site-settings/General', [
            'siteSettings' => [
                'site_name' => $siteSettings->site_name,
                'site_tagline' => $siteSettings->site_tagline,
                'currency' => $siteSettings->currency,
            ],
            'currencyOptions' => self::CURRENCY_OPTIONS,
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
            'currency' => 'USD',
        ]);
    }
}
