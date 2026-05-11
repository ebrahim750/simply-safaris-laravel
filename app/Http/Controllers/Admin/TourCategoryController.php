<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourCategoryRequest;
use App\Http\Requests\Admin\UpdateTourCategoryRequest;
use App\Models\TourCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TourCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/tours/Categories', [
            'categories' => TourCategory::query()
                ->latest('id')
                ->with('tours:id,name,main_image')
                ->get()
                ->map(fn (TourCategory $category): array => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'image_url' => $category->image ? Storage::disk('public')->url($category->image) : null,
                    'tours' => $category->tours->map(fn ($tour) => [
                        'id' => $tour->id,
                        'name' => $tour->name,
                        'main_image' => blank($tour->main_image)
                            ? null
                            : (Str::startsWith($tour->main_image, ['http://', 'https://', '/'])
                                ? $tour->main_image
                                : Storage::disk('public')->url($tour->main_image)),
                    ])->all(),
                    'created_at' => $category->created_at?->toIso8601String(),
                    'created_at_display' => $category->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }

    public function store(StoreTourCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $category = TourCategory::query()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => '',
        ]);

        if ($request->hasFile('image')) {
            $category->forceFill([
                'image' => $this->storeImage($request->file('image'), 'categories/'.$category->id),
            ])->save();
        }

        return to_route('admin.tours.categories');
    }

    public function update(UpdateTourCategoryRequest $request, TourCategory $category): RedirectResponse
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $this->storeImage($request->file('image'), 'categories/'.$category->id);
        }

        $category->update($data);

        return to_route('admin.tours.categories');
    }

    public function destroy(TourCategory $category): RedirectResponse
    {
        $category->tours()->detach();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return to_route('admin.tours.categories');
    }

    private function storeImage(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }
}
