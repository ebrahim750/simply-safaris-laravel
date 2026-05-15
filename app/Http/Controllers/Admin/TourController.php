<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourRequest;
use App\Http\Requests\Admin\UpdateTourRequest;
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
                ->with(['categories:id,name', 'activities:id,name', 'places:id,name'])
                ->get(['id', 'name', 'main_image', 'price', 'is_hidden', 'is_featured_on_home', 'is_featured_as_seasonal_recommendation', 'show_external_booking', 'created_at'])
                ->map(fn (Tour $tour): array => $this->mapIndexTour($tour, $currency)),
        ]);
    }

    public function create(): Response
    {
        $currency = SiteSetting::query()->value('currency') ?: 'USD';

        return Inertia::render('admin/tours/Create', [
            'currency' => $currency,
            'categories' => TourCategory::query()
                ->orderBy('name')
                ->get(['id', 'name', 'image'])
                ->map(fn (TourCategory $category): array => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => blank($category->image)
                        ? null
                        : (Str::startsWith($category->image, ['http://', 'https://', '/'])
                            ? $category->image
                            : Storage::disk('public')->url($category->image)),
                ]),
            'activities' => TourActivity::query()
                ->orderBy('name')
                ->get(['id', 'name', 'image'])
                ->map(fn (TourActivity $activity): array => [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'image' => blank($activity->image)
                        ? null
                        : (Str::startsWith($activity->image, ['http://', 'https://', '/'])
                            ? $activity->image
                            : Storage::disk('public')->url($activity->image)),
                ]),
            'places' => Place::query()
                ->with('country:id,name,country_code')
                ->orderBy('name')
                ->get(['id', 'country_id', 'name'])
                ->map(fn (Place $place): array => [
                    'id' => $place->id,
                    'name' => $place->name,
                    'country_id' => $place->country_id,
                    'country_name' => $place->country?->name ?? 'N/A',
                    'country_code' => $place->country?->country_code ?? null,
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
                'show_external_booking' => $validated['show_external_booking'],
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

    public function edit(Tour $tour): Response
    {
        $currency = SiteSetting::query()->value('currency') ?: 'USD';

        return Inertia::render('admin/tours/Edit', [
            'currency' => $currency,
            'tour' => [
                'id' => $tour->id,
                'name' => $tour->name,
                'price' => $tour->price,
                'main_image' => blank($tour->main_image)
                    ? null
                    : (Str::startsWith($tour->main_image, ['http://', 'https://', '/'])
                        ? $tour->main_image
                        : Storage::disk('public')->url($tour->main_image)),
                'gallery_images' => collect($tour->gallery_images ?? [])
                    ->map(fn (string $path): string => Str::startsWith($path, ['http://', 'https://', '/'])
                        ? $path
                        : Storage::disk('public')->url($path))
                    ->values()
                    ->all(),
                'external_url' => $tour->external_url,
                'external_link_button_text' => $tour->external_link_button_text,
                'show_external_booking' => $tour->show_external_booking,
                'duration_days' => $tour->duration_days,
                'overview' => $tour->overview,
                'pricing_detailed' => $tour->pricing_detailed,
                'is_hidden' => $tour->is_hidden,
                'is_featured_on_home' => $tour->is_featured_on_home,
                'is_featured_as_seasonal_recommendation' => $tour->is_featured_as_seasonal_recommendation,
                'category_ids' => $tour->categories->pluck('id')->all(),
                'activity_ids' => $tour->activities->pluck('id')->all(),
                'place_ids' => $tour->places->pluck('id')->all(),
                'itinerary_items' => $tour->itineraryItems->map(fn ($item): array => [
                    'title' => $item->title,
                    'content' => $item->content,
                ])->all(),
                'inclusions' => $tour->inclusions->map(fn ($item): array => [
                    'text' => $item->text,
                ])->all(),
                'exclusions' => $tour->exclusions->map(fn ($item): array => [
                    'text' => $item->text,
                ])->all(),
            ],
            'categories' => TourCategory::query()
                ->orderBy('name')
                ->get(['id', 'name', 'image'])
                ->map(fn (TourCategory $category): array => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => blank($category->image)
                        ? null
                        : (Str::startsWith($category->image, ['http://', 'https://', '/'])
                            ? $category->image
                            : Storage::disk('public')->url($category->image)),
                ]),
            'activities' => TourActivity::query()
                ->orderBy('name')
                ->get(['id', 'name', 'image'])
                ->map(fn (TourActivity $activity): array => [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'image' => blank($activity->image)
                        ? null
                        : (Str::startsWith($activity->image, ['http://', 'https://', '/'])
                            ? $activity->image
                            : Storage::disk('public')->url($activity->image)),
                ]),
            'places' => Place::query()
                ->with('country:id,name,country_code')
                ->orderBy('name')
                ->get(['id', 'country_id', 'name'])
                ->map(fn (Place $place): array => [
                    'id' => $place->id,
                    'name' => $place->name,
                    'country_id' => $place->country_id,
                    'country_name' => $place->country?->name ?? 'N/A',
                    'country_code' => $place->country?->country_code ?? null,
                ]),
        ]);
    }

    public function update(UpdateTourRequest $request, Tour $tour): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $tour): void {
            $tour->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'external_url' => $validated['external_url'] ?? null,
                'external_link_button_text' => $validated['external_link_button_text'] ?? null,
                'show_external_booking' => $validated['show_external_booking'],
                'duration_days' => $validated['duration_days'] ?? null,
                'overview' => $validated['overview'] ?? null,
                'pricing_detailed' => $validated['pricing_detailed'] ?? null,
                'is_hidden' => $validated['is_hidden'],
                'is_featured_on_home' => $validated['is_featured_on_home'],
                'is_featured_as_seasonal_recommendation' => $validated['is_featured_as_seasonal_recommendation'],
            ]);

            $tourFolder = 'tours/'.$tour->id;

            if ($request->hasFile('main_image')) {
                if ($tour->main_image) {
                    Storage::disk('public')->delete($tour->main_image);
                }
                $tour->forceFill([
                    'main_image' => $this->storeImage($request->file('main_image'), $tourFolder),
                ])->save();
            }

            if ($request->hasFile('gallery_images')) {
                if ($tour->gallery_images) {
                    foreach ($tour->gallery_images as $path) {
                        Storage::disk('public')->delete($path);
                    }
                }
                $tour->forceFill([
                    'gallery_images' => $this->storeGalleryImages($request->file('gallery_images', []), $tourFolder),
                ])->save();
            }

            $tour->categories()->sync($this->buildPivotPayload($validated['category_ids'] ?? []));
            $tour->activities()->sync($this->buildPivotPayload($validated['activity_ids'] ?? []));
            $tour->places()->sync($this->buildPivotPayload($validated['place_ids'] ?? []));

            $tour->itineraryItems()->delete();
            $tour->itineraryItems()->createMany($this->buildPositionedRecords(
                $validated['itinerary_items'] ?? [],
                'title',
                'content',
            ));
            $tour->inclusions()->delete();
            $tour->inclusions()->createMany($this->buildPositionedRecords(
                $validated['inclusions'] ?? [],
                'text',
            ));
            $tour->exclusions()->delete();
            $tour->exclusions()->createMany($this->buildPositionedRecords(
                $validated['exclusions'] ?? [],
                'text',
            ));
        });

        return to_route('admin.tours')
            ->with('status', 'Tour updated.');
    }

    public function toggleHidden(Tour $tour): RedirectResponse
    {
        $tour->forceFill([
            'is_hidden' => ! $tour->is_hidden,
        ])->save();

        return back();
    }

    public function destroy(Tour $tour): RedirectResponse
    {
        $tour->delete();

        return to_route('admin.tours')
            ->with('status', 'Tour deleted.');
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
            'show_external_booking' => $tour->show_external_booking,
            'categories' => $tour->categories->map(fn ($category) => ['id' => $category->id, 'name' => $category->name])->all(),
            'places' => $tour->places->map(fn ($place) => ['id' => $place->id, 'name' => $place->name])->all(),
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
