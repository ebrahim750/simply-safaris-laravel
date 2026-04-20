<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TourCategoryController extends Controller
{
    public function index(Request $request): Response
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
}
