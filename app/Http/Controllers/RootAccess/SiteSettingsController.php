<?php

namespace App\Http\Controllers\RootAccess;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('root-access/SiteSettings');
    }
}
