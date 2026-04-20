<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourCategoryRequest;
use App\Http\Requests\Admin\UpdateTourCategoryRequest;
use App\Models\TourCategory;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TourCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/tours/Categories', [
            'categories' => TourCategory::query()
                ->latest('id')
                ->get(['id', 'name', 'created_at'])
                ->map(fn (TourCategory $category): array => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'created_at' => $category->created_at?->toIso8601String(),
                    'created_at_display' => $category->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }

    public function store(StoreTourCategoryRequest $request): RedirectResponse
    {
        TourCategory::query()->create($request->validated());

        return to_route('admin.tours.categories');
    }

    public function update(UpdateTourCategoryRequest $request, TourCategory $category): RedirectResponse
    {
        $category->update($request->validated());

        return to_route('admin.tours.categories');
    }

    public function destroy(TourCategory $category): RedirectResponse
    {
        $category->tours()->detach();
        $category->delete();

        return to_route('admin.tours.categories');
    }
}
