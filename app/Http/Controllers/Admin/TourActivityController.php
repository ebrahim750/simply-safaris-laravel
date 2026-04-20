<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourActivityRequest;
use App\Http\Requests\Admin\UpdateTourActivityRequest;
use App\Models\TourActivity;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TourActivityController extends Controller
{
    public function index(): Response
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

    public function store(StoreTourActivityRequest $request): RedirectResponse
    {
        TourActivity::query()->create($request->validated());

        return to_route('admin.tours.activities');
    }

    public function update(UpdateTourActivityRequest $request, TourActivity $activity): RedirectResponse
    {
        $activity->update($request->validated());

        return to_route('admin.tours.activities');
    }

    public function destroy(TourActivity $activity): RedirectResponse
    {
        $activity->tours()->detach();
        $activity->delete();

        return to_route('admin.tours.activities');
    }
}
