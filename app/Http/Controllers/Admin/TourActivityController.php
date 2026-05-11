<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourActivityRequest;
use App\Http\Requests\Admin\UpdateTourActivityRequest;
use App\Models\TourActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TourActivityController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/tours/Activities', [
            'activities' => TourActivity::query()
                ->latest('id')
                ->with('tours:id,name,main_image')
                ->get()
                ->map(fn (TourActivity $activity): array => [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'description' => $activity->description,
                    'image_url' => $activity->image ? Storage::disk('public')->url($activity->image) : null,
                    'tours' => $activity->tours->map(fn ($tour) => [
                        'id' => $tour->id,
                        'name' => $tour->name,
                        'main_image' => blank($tour->main_image)
                            ? null
                            : (Str::startsWith($tour->main_image, ['http://', 'https://', '/'])
                                ? $tour->main_image
                                : Storage::disk('public')->url($tour->main_image)),
                    ])->all(),
                    'created_at' => $activity->created_at?->toIso8601String(),
                    'created_at_display' => $activity->created_at?->format('M j, Y g:i A') ?? 'N/A',
                ]),
        ]);
    }

    public function store(StoreTourActivityRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $activity = TourActivity::query()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => '',
        ]);

        if ($request->hasFile('image')) {
            $activity->forceFill([
                'image' => $this->storeImage($request->file('image'), 'activities/'.$activity->id),
            ])->save();
        }

        return to_route('admin.tours.activities');
    }

    public function update(UpdateTourActivityRequest $request, TourActivity $activity): RedirectResponse
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $data['image'] = $this->storeImage($request->file('image'), 'activities/'.$activity->id);
        }

        $activity->update($data);

        return to_route('admin.tours.activities');
    }

    public function destroy(TourActivity $activity): RedirectResponse
    {
        $activity->tours()->detach();

        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();

        return to_route('admin.tours.activities');
    }

    private function storeImage(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }
}
