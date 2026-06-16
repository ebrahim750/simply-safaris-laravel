<?php

namespace App\Http\Requests\Admin;

use App\Models\BlogCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->account_type, ['admin', 'root'], true);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim((string) $this->input('title')),
            'content' => $this->nullableTrimmedString('content'),
            'is_published' => $this->boolean('is_published'),
            'category_ids' => $this->normalizeIdentifierArray('category_ids'),
            'published_at' => $this->boolean('is_published') ? ($this->input('published_at') ?: now()) : null,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $postId = $this->route('post')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($postId)],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', File::image()->max(10 * 1024)],
            'is_published' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['required', 'integer', 'distinct', 'exists:'.(new BlogCategory)->getTable().',id'],
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
}
