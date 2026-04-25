<?php

namespace App\Http\Requests\Admin;

use App\Models\Country;
use App\Models\Place;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDestinationPlaceRequest extends FormRequest
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
            'country_id' => $this->input('country_id'),
            'overview' => $this->nullableTrimmedString('overview'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Place::class, 'name')
                    ->where(fn ($query) => $query->where('country_id', $this->integer('country_id'))),
            ],
            'country_id' => ['required', 'integer', 'exists:'.(new Country)->getTable().',id'],
            'overview' => ['nullable', 'string'],
        ];
    }

    private function nullableTrimmedString(string $key): ?string
    {
        $value = trim((string) $this->input($key));

        return $value === '' ? null : $value;
    }
}
