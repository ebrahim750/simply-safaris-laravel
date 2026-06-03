<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Place;
use Inertia\Inertia;
use Inertia\Response;

class DestinationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('destinations/Index', [
            'countries' => Country::query()
                ->with(['places' => fn ($query) => $query->select('id', 'country_id', 'name', 'slug')->orderBy('name')])
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'main_image', 'overview']),
        ]);
    }

    public function showCountry(Country $country): Response
    {
        return Inertia::render('destinations/Country', [
            'country' => $country->load(['places' => fn ($query) => $query->orderBy('name')]),
        ]);
    }

    public function showPlace(Country $country, Place $place): Response
    {
        return Inertia::render('destinations/Place', [
            'country' => $country,
            'place' => $place->load('country'),
        ]);
    }
}
