<?php

namespace App\Http\Controllers;

use App\Mail\TourEnquirySubmitted;
use App\Models\Country;
use App\Models\SiteSetting;
use App\Models\Tour;
use App\Models\TourActivity;
use App\Models\TourCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(Request $request): Response
    {
        $tours = Tour::query()
            ->where('is_hidden', false)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->input('search').'%';
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('overview', 'like', $search)
                        ->orWhere('pricing_detailed', 'like', $search)
                        ->orWhereHas('places', function ($q) use ($search) {
                            $q->where('name', 'like', $search)
                                ->orWhereHas('country', fn ($q) => $q->where('name', 'like', $search));
                        })
                        ->orWhereHas('categories', fn ($q) => $q->where('name', 'like', $search))
                        ->orWhereHas('activities', fn ($q) => $q->where('name', 'like', $search));
                });
            })
            ->when($request->filled('categories'), fn ($query) => $query->whereHas('categories', fn ($q) => $q->whereIn('tour_categories.id', $request->array('categories'))))
            ->when($request->filled('activities'), fn ($query) => $query->whereHas('activities', fn ($q) => $q->whereIn('tour_activities.id', $request->array('activities'))))
            ->when($request->filled('places'), fn ($query) => $query->whereHas('places', fn ($q) => $q->whereIn('places.id', $request->array('places'))))
            ->when($request->filled('countries'), fn ($query) => $query->whereHas('places', fn ($q) => $q->whereIn('country_id', $request->array('countries'))))
            ->when($request->filled('price_min') || $request->filled('price_max'), function ($query) use ($request) {
                $min = $request->input('price_min', 0);
                $max = $request->input('price_max', PHP_INT_MAX);
                $query->whereBetween('price', [(float) $min, (float) $max]);
            })
            ->when($request->filled('duration_min') || $request->filled('duration_max'), function ($query) use ($request) {
                $min = $request->input('duration_min', 0);
                $max = $request->input('duration_max', PHP_INT_MAX);
                $query->whereBetween('duration_days', [(int) $min, (int) $max]);
            })
            ->latest('id')
            ->with(['categories:id,name', 'places:id,name,country_id', 'places.country:id,name,country_code'])
            ->paginate(12)
            ->through(fn (Tour $tour): array => $this->mapTour($tour))
            ->withQueryString();

        $countries = Country::query()
            ->with(['places' => fn ($query) => $query->select('id', 'country_id', 'name')->orderBy('name')])
            ->orderBy('name')
            ->get(['id', 'name', 'country_code'])
            ->map(fn ($country) => [
                'id' => $country->id,
                'name' => $country->name,
                'country_code' => $country->country_code,
                'places' => $country->places->map(fn ($place) => ['id' => $place->id, 'name' => $place->name])->all(),
            ]);

        $activities = TourActivity::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = TourCategory::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $filters = [
            'search' => $request->input('search'),
            'categories' => array_map('intval', $request->array('categories', [])),
            'activities' => array_map('intval', $request->array('activities', [])),
            'places' => array_map('intval', $request->array('places', [])),
            'countries' => array_map('intval', $request->array('countries', [])),
            'price' => [
                'min' => (int) $request->input('price_min', 100),
                'max' => (int) $request->input('price_max', 5000),
            ],
            'duration' => [
                'min' => (int) $request->input('duration_min', 1),
                'max' => (int) $request->input('duration_max', 30),
            ],
        ];

        return Inertia::render('tours/Index', [
            'tours' => $tours,
            'countries' => $countries,
            'activities' => $activities,
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    public function search(): JsonResponse
    {
        $query = request('q', '');

        if (blank($query)) {
            return response()->json(['results' => []]);
        }

        $tours = Tour::query()
            ->where('is_hidden', false)
            ->where('name', 'like', '%'.$query.'%')
            ->limit(8)
            ->with(['places:id,name,country_id', 'places.country:id,name,country_code'])
            ->get(['id', 'name', 'slug', 'main_image', 'duration_days', 'price']);

        return response()->json([
            'results' => $tours->map(fn (Tour $tour): array => [
                'id' => $tour->id,
                'name' => $tour->name,
                'url' => route('tours.show', $tour),
                'main_image' => $this->resolveImageUrl($tour->main_image) ?? '/images/defaults/600x400.png',
                'duration_days' => $tour->duration_days,
                'price' => Number::currency($tour->price, precision: 0),
                'places' => $tour->places->map(fn ($p): array => [
                    'name' => $p->name,
                    'country' => $p->country?->name,
                    'country_code' => $p->country?->country_code,
                ])->all(),
            ])->all(),
        ]);
    }

    public function show(Tour $tour): Response
    {
        $tour->load(['categories:id,name', 'activities:id,name', 'places:id,name,country_id', 'places.country:id,name,country_code', 'itineraryItems', 'inclusions', 'exclusions']);

        $seasonalRecommendations = Tour::query()
            ->where('is_hidden', false)
            ->where('is_featured_as_seasonal_recommendation', true)
            ->where('id', '!=', $tour->id)
            ->with(['categories:id,name', 'places:id,name,country_id', 'places.country:id,name,country_code'])
            ->limit(12)
            ->latest('id')
            ->get()
            ->map(fn (Tour $recommendation): array => $this->mapTour($recommendation))
            ->all();

        return Inertia::render('tours/Show', [
            'tour' => [
                'id' => $tour->id,
                'name' => $tour->name,
                'main_image' => $this->resolveImageUrl($tour->main_image),
                'gallery_images' => collect($tour->gallery_images ?? [])
                    ->map(fn (string $path): ?string => $this->resolveImageUrl($path))
                    ->filter()
                    ->values()
                    ->all(),
                'price' => Number::currency($tour->price, precision: 0),
                'price_amount' => (float) $tour->price,
                'duration_days' => $tour->duration_days,
                'overview' => $tour->overview,
                'pricing_detailed' => $tour->pricing_detailed,
                'external_url' => $tour->external_url,
                'external_link_button_text' => $tour->external_link_button_text,
                'show_external_booking' => $tour->show_external_booking,
                'categories' => $tour->categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->all(),
                'activities' => $tour->activities->map(fn ($a) => ['id' => $a->id, 'name' => $a->name])->all(),
                'places' => $tour->places->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'country' => $p->country?->name, 'country_code' => $p->country?->country_code])->all(),
                'itinerary_items' => $tour->itineraryItems->map(fn ($i) => ['title' => $i->title, 'content' => $i->content])->all(),
                'inclusions' => $tour->inclusions->map(fn ($i) => ['text' => $i->text])->all(),
                'exclusions' => $tour->exclusions->map(fn ($e) => ['text' => $e->text])->all(),
            ],
            'turnstileSiteKey' => config('services.cf_turnstile.site_key'),
            'isLocal' => app()->environment('local'),
            'seasonalRecommendations' => $seasonalRecommendations,
        ]);
    }

    public function enquiry(Request $request, Tour $tour): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'booking_date' => ['required', 'date', 'after:today'],
            'adults' => ['required', 'string', 'max:10'],
            'children' => ['nullable', 'string', 'max:10'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        if (! app()->environment('local')) {
            $turnstileResponse = Http::timeout(5)->asForm()->post(
                'https://challenges.cloudflare.com/turnstile/v0/siteverify',
                [
                    'secret' => config('services.cf_turnstile.secret'),
                    'response' => $request->input('cf-turnstile-response'),
                    'remoteip' => $request->ip(),
                ]
            );

            if (! $turnstileResponse->json('success')) {
                throw ValidationException::withMessages([
                    'cf-turnstile-response' => ['Please complete the security challenge.'],
                ]);
            }
        }

        $enquiry = $tour->enquiries()->create(array_merge($validated, [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]));

        $siteSettings = SiteSetting::query()->first();

        if (
            $siteSettings
            && $siteSettings->form_emails_enabled
            && filled($siteSettings->form_emails)
        ) {
            $recipients = collect(explode(',', $siteSettings->form_emails))
                ->map(fn (string $email): string => trim($email))
                ->filter(fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
                ->values()
                ->all();

            if (count($recipients) > 0) {
                Mail::to($recipients)->queue(new TourEnquirySubmitted(
                    enquiry: $enquiry,
                    tour: $tour,
                ));
            }
        }

        return redirect()->back()->with('status', 'Your enquiry has been submitted. We will get back to you soon.');
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTour(Tour $tour): array
    {
        return [
            'id' => $tour->id,
            'name' => $tour->name,
            'main_image' => $this->resolveImageUrl($tour->main_image),
            'price' => Number::currency($tour->price, precision: 0),
            'price_amount' => (float) $tour->price,
            'duration_days' => $tour->duration_days,
            'categories' => $tour->categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->all(),
            'places' => $tour->places->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'country' => $p->country?->name, 'country_code' => $p->country?->country_code])->all(),
        ];
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::contains($path, ['via.placeholder.com', 'placehold.co'])) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://', '/'])
            ? $path
            : Storage::disk('public')->url($path);
    }
}
