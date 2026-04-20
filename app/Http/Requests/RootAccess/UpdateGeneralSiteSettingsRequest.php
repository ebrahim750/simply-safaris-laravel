<?php

namespace App\Http\Requests\RootAccess;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->account_type === 'root';
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
        ];
    }
}
