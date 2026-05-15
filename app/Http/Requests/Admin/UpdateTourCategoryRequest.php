<?php

namespace App\Http\Requests\Admin;

use App\Models\TourCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTourCategoryRequest extends FormRequest
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
            'description' => trim((string) $this->input('description')) ?: null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var TourCategory|null $category */
        $category = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(TourCategory::class, 'name')->ignore($category),
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:5120',
            ],
            'remove_image' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
