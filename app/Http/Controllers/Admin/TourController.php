<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourRequest;
use App\Models\Place;
use App\Models\SiteSetting;
use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\TourCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(Request $request): Response
    {
        $currency = SiteSetting::query()->value('currency') ?: 'USD';

        return Inertia::render('admin/tours/Tours', [
            'tours' => Tour::query()
                ->latest('id')
                ->get(['id', 'name', 'main_image', 'price', 'is_hidden', 'is_featured_on_home', 'is_featured_as_seasonal_recommendation', 'created_at'])
                ->map(fn (Tour $tour): array => $this->mapIndexTour($tour, $currency)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/tours/Create', [
            'categories' => TourCategory::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (TourCategory $category): array => [
                    'id' => $category->id,
                    'name' => $category->name,
                ]),
            'activities' => TourActivity::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (TourActivity $activity): array => [
                    'id' => $activity->id,
                    'name' => $activity->name,
                ]),
            'places' => Place::query()
                ->with('country:id,name')
                ->orderBy('name')
                ->get(['id', 'country_id', 'name'])
                ->map(fn (Place $place): array => [
                    'id' => $place->id,
                    'name' => $place->name,
                    'country_id' => $place->country_id,
                    'country_name' => $place->country?->name ?? 'N/A',
                ]),
        ]);
    }

    public function store(StoreTourRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated): void {
            $tour = Tour::query()->create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'main_image' => '',
                'gallery_images' => null,
                'external_url' => $validated['external_url'] ?? null,
                'external_link_button_text' => $validated['external_link_button_text'] ?? null,
                'duration_days' => $validated['duration_days'] ?? null,
                'overview' => $validated['overview'] ?? null,
                'pricing_detailed' => $validated['pricing_detailed'] ?? null,
                'is_hidden' => $validated['is_hidden'],
                'is_featured_on_home' => $validated['is_featured_on_home'],
                'is_featured_as_seasonal_recommendation' => $validated['is_featured_as_seasonal_recommendation'],
            ]);

            $tourFolder = 'tours/'.$tour->id;

            $tour->forceFill([
                'main_image' => $this->storeImage($request->file('main_image'), $tourFolder),
                'gallery_images' => $this->storeGalleryImages($request->file('gallery_images', []), $tourFolder),
            ])->save();

            $tour->categories()->sync($this->buildPivotPayload($validated['category_ids'] ?? []));
            $tour->activities()->sync($this->buildPivotPayload($validated['activity_ids'] ?? []));
            $tour->places()->sync($this->buildPivotPayload($validated['place_ids'] ?? []));

            $tour->itineraryItems()->createMany($this->buildPositionedRecords(
                $validated['itinerary_items'] ?? [],
                'title',
                'content',
            ));
            $tour->inclusions()->createMany($this->buildPositionedRecords(
                $validated['inclusions'] ?? [],
                'text',
            ));
            $tour->exclusions()->createMany($this->buildPositionedRecords(
                $validated['exclusions'] ?? [],
                'text',
            ));
        });

        return to_route('admin.tours')
            ->with('status', 'Tour created.');
    }

    public function toggleHidden(Tour $tour): RedirectResponse
    {
        $tour->forceFill([
            'is_hidden' => ! $tour->is_hidden,
        ])->save();

        return back()->with(
            'status',
            $tour->is_hidden ? 'Tour hidden.' : 'Tour unhidden.',
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function mapIndexTour(Tour $tour, string $currency): array
    {
        return [
            'id' => $tour->id,
            'name' => $tour->name,
            'main_image' => blank($tour->main_image)
                ? null
                : (Str::startsWith($tour->main_image, ['http://', 'https://', '/'])
                    ? $tour->main_image
                    : Storage::disk('public')->url($tour->main_image)),
            'price_amount' => (float) $tour->price,
            'price' => Number::currency($tour->price, in: $currency, precision: 0),
            'is_hidden' => $tour->is_hidden,
            'is_featured_on_home' => $tour->is_featured_on_home,
            'is_featured_as_seasonal_recommendation' => $tour->is_featured_as_seasonal_recommendation,
            'created_at' => $tour->created_at?->toIso8601String(),
            'created_at_display' => $tour->created_at?->format('M j, Y g:i A') ?? 'N/A',
        ];
    }

    private function storeImage(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    /**
     * @param  array<int, UploadedFile>|UploadedFile|null  $files
     * @return array<int, string>|null
     */
    private function storeGalleryImages(array|UploadedFile|null $files, string $directory): ?array
    {
        if ($files instanceof UploadedFile) {
            $files = [$files];
        }

        if (! is_array($files) || $files === []) {
            return null;
        }

        return array_values(array_map(
            fn (UploadedFile $file): string => $this->storeImage($file, $directory.'/gallery'),
            $files,
        ));
    }

    /**
     * @param  array<int, int>  $ids
     * @return array<int, array{position: int}>
     */
    private function buildPivotPayload(array $ids): array
    {
        $payload = [];

        foreach (array_values($ids) as $index => $id) {
            $payload[$id] = [
                'position' => $index + 1,
            ];
        }

        return $payload;
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    private function buildPositionedRecords(array $items, string ...$fields): array
    {
        $records = [];

        foreach (array_values($items) as $index => $item) {
            $record = [
                'position' => $index + 1,
            ];

            foreach ($fields as $field) {
                $record[$field] = $item[$field] ?? null;
            }

            $records[] = $record;
        }

        return $records;
    }
}
