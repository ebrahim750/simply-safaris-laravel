<?php

namespace App\Http\Requests\Admin;

use App\Models\Country;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDestinationCountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->account_type, ['admin', 'root'], true);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $country = $this->route('country');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Country::class, 'name')
                    ->ignore($country),
            ],
            'overview' => ['nullable', 'string'],
            'main_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'best_time_to_go' => ['nullable', 'string'],
            'high_season' => ['nullable', 'string'],
            'electrical' => ['nullable', 'string'],
            'tourist_info' => ['nullable', 'string', 'max:255'],
        ];
    }
}
