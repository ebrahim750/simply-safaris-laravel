<?php

namespace App\Http\Requests\RootAccess;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColorSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->account_type === 'root';
    }

    public function rules(): array
    {
        return [
            'primary_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'secondary_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'accent_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
        ];
    }
}
