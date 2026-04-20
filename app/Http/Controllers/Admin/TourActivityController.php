<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TourActivityController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('admin/tours/Activities', [
            'activities' => TourActivity::query()
                ->latest('id')
                ->get(['id', 'name', 'created_at'])
                ->map(fn (TourActivity $activity): array => [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'created_at' => $activity->created_at?->toIso8601String(),
                    'created_at_display' => $activity->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }
}
