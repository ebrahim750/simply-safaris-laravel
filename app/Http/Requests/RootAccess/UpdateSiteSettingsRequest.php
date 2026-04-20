<?php

namespace App\Http\Requests\RootAccess;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateSiteSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->account_type === 'root';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'primary_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'secondary_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'accent_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'logo' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'webp', 'svg'])->max(2048)],
            'admin_logo_light' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'webp', 'svg'])->max(2048)],
            'admin_logo_dark' => ['nullable', File::types(['jpg', 'jpeg', 'png', 'webp', 'svg'])->max(2048)],
            'favicon' => ['nullable', File::types(['ico', 'png', 'svg'])->max(1024)],
        ];
    }
}
