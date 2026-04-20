<?php

namespace App\Http\Requests\RootAccess;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateLogoSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->account_type === 'root';
    }

    public function rules(): array
    {
        return [
            'logo' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'webp', 'svg'])->max(2048)],
            'admin_logo_light' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'webp', 'svg'])->max(2048)],
            'admin_logo_dark' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'webp', 'svg'])->max(2048)],
            'favicon' => ['nullable', File::types(['ico', 'png', 'svg'])->max(1024)],
        ];
    }
}
