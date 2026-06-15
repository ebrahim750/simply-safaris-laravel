<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourEnquiry;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FormSubmissionController extends Controller
{
    public function contact(): Response
    {
        return Inertia::render('admin/form-submissions/Contact');
    }

    public function tour(Request $request): Response
    {
        $search = $request->query('search');

        $enquiries = TourEnquiry::query()
            ->with('tour:id,name')
            ->when(
                is_string($search) && $search !== '',
                function ($query) use ($search): void {
                    $term = '%'.mb_strtolower($search).'%';

                    $query->where(function ($query) use ($term): void {
                        $query
                            ->whereRaw('LOWER(name) LIKE ?', [$term])
                            ->orWhereRaw('LOWER(email) LIKE ?', [$term])
                            ->orWhereRaw('LOWER(phone) LIKE ?', [$term])
                            ->orWhereRaw('LOWER(message) LIKE ?', [$term])
                            ->orWhereHas('tour', function ($query) use ($term): void {
                                $query->whereRaw('LOWER(name) LIKE ?', [$term]);
                            });
                    });
                }
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('admin/form-submissions/Tour', [
            'enquiries' => $enquiries,
        ]);
    }
}
