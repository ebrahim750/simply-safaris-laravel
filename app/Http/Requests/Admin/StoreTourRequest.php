<?php

namespace App\Http\Requests\Admin;

use App\Models\Destination;
use App\Models\TourActivity;
use App\Models\TourCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreTourRequest extends FormRequest
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
            'price' => trim((string) $this->input('price')),
            'external_url' => $this->nullableTrimmedString('external_url'),
            'external_link_button_text' => $this->nullableTrimmedString('external_link_button_text'),
            'duration_days' => $this->nullableTrimmedString('duration_days'),
            'overview' => $this->nullableTrimmedString('overview'),
            'pricing_detailed' => $this->nullableTrimmedString('pricing_detailed'),
            'is_hidden' => $this->boolean('is_hidden'),
            'is_featured_on_home' => $this->boolean('is_featured_on_home'),
            'is_featured_as_seasonal_recommendation' => $this->boolean('is_featured_as_seasonal_recommendation'),
            'category_ids' => $this->normalizeIdentifierArray('category_ids'),
            'activity_ids' => $this->normalizeIdentifierArray('activity_ids'),
            'destination_ids' => $this->normalizeIdentifierArray('destination_ids'),
            'itinerary_items' => $this->normalizeItineraryItems(),
            'inclusions' => $this->normalizeTextRows('inclusions'),
            'exclusions' => $this->normalizeTextRows('exclusions'),
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
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'main_image' => ['required', File::image()->max(10 * 1024)],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['required', File::image()->max(10 * 1024)],
            'external_url' => ['nullable', 'url', 'max:255'],
            'external_link_button_text' => ['nullable', 'string', 'max:255'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'overview' => ['nullable', 'string'],
            'pricing_detailed' => ['nullable', 'string'],
            'is_hidden' => ['required', 'boolean'],
            'is_featured_on_home' => ['required', 'boolean'],
            'is_featured_as_seasonal_recommendation' => ['required', 'boolean'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['required', 'integer', 'distinct', 'exists:'.(new TourCategory)->getTable().',id'],
            'activity_ids' => ['nullable', 'array'],
            'activity_ids.*' => ['required', 'integer', 'distinct', 'exists:'.(new TourActivity)->getTable().',id'],
            'destination_ids' => ['nullable', 'array'],
            'destination_ids.*' => ['required', 'integer', 'distinct', 'exists:'.(new Destination)->getTable().',id'],
            'itinerary_items' => ['nullable', 'array'],
            'itinerary_items.*.title' => ['required', 'string', 'max:255'],
            'itinerary_items.*.content' => ['nullable', 'string'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*.text' => ['required', 'string'],
            'exclusions' => ['nullable', 'array'],
            'exclusions.*.text' => ['required', 'string'],
        ];
    }

    private function nullableTrimmedString(string $key): ?string
    {
        $value = trim((string) $this->input($key));

        return $value === '' ? null : $value;
    }

    /**
     * @return array<int, int>
     */
    private function normalizeIdentifierArray(string $key): array
    {
        $values = $this->input($key, []);

        if (! is_array($values)) {
            return [];
        }

        return array_values(array_filter($values, static function (mixed $value): bool {
            return $value !== null && $value !== '';
        }));
    }

    /**
     * @return array<int, array{title: string, content: ?string}>
     */
    private function normalizeItineraryItems(): array
    {
        $items = $this->input('itinerary_items', []);

        if (! is_array($items)) {
            return [];
        }

        return array_values(array_filter(array_map(function (mixed $item): array {
            $title = trim((string) data_get($item, 'title'));
            $content = trim((string) data_get($item, 'content'));

            return [
                'title' => $title,
                'content' => $content === '' ? null : $content,
            ];
        }, $items), static function (array $item): bool {
            return $item['title'] !== '' || $item['content'] !== null;
        }));
    }

    /**
     * @return array<int, array{text: string}>
     */
    private function normalizeTextRows(string $key): array
    {
        $items = $this->input($key, []);

        if (! is_array($items)) {
            return [];
        }

        return array_values(array_filter(array_map(function (mixed $item): array {
            return [
                'text' => trim((string) data_get($item, 'text')),
            ];
        }, $items), static function (array $item): bool {
            return $item['text'] !== '';
        }));
    }
}
