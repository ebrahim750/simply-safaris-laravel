<?php

namespace App\Http\Requests\RootAccess;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGeneralSiteSettingsRequest extends FormRequest
{
    private const ALLOWED_CURRENCIES = [
        'USD',
        'EUR',
        'GBP',
        'KES',
        'TZS',
        'UGX',
        'ZAR',
    ];

    public function authorize(): bool
    {
        return $this->user()?->account_type === 'root';
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'size:3', Rule::in(self::ALLOWED_CURRENCIES)],
        ];
    }
}
