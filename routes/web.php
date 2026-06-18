<?php

use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\DestinationCountryController;
use App\Http\Controllers\Admin\DestinationPlaceController;
use App\Http\Controllers\Admin\FormSubmissionController;
use App\Http\Controllers\Admin\TourActivityController;
use App\Http\Controllers\Admin\TourCategoryController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\RootAccess\ColorSiteSettingsController;
use App\Http\Controllers\RootAccess\EmailSiteSettingsController;
use App\Http\Controllers\RootAccess\GeneralSiteSettingsController;
use App\Http\Controllers\RootAccess\LogoSiteSettingsController;
use App\Http\Controllers\RootAccess\ProdTestController;
use App\Http\Controllers\RootAccess\SiteSettingsController;
use App\Http\Controllers\RootAccess\UserController;
use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');
Route::get('/home2', [BlogController::class, 'home2'])->name('home2');
Route::inertia('/about', 'About')->name('about');
Route::inertia('/about2', 'AboutTailwind')->name('about2');
Route::get('/contact', [ContactController::class, 'contact'])->name('contact');
Route::get('/contact2', [ContactController::class, 'contact2'])->name('contact2');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{country:slug}', [DestinationController::class, 'showCountry'])->name('destinations.country');

Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/search', [TourController::class, 'search'])->name('tours.search');
Route::get('/tours/{tour}', [TourController::class, 'show'])->name('tours.show');
Route::post('/tours/{tour}/enquiry', [TourController::class, 'enquiry'])->name('tours.enquiry');
Route::get('/tours2', [TourController::class, 'index2'])->name('tours2.index');
Route::get('/tours2/search', [TourController::class, 'search'])->name('tours2.search');
Route::get('/tours2/{tour}', [TourController::class, 'show2'])->name('tours2.show');
Route::post('/tours2/{tour}/enquiry', [TourController::class, 'enquiry'])->name('tours2.enquiry');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog2', [BlogController::class, 'index2'])->name('blog2.index');
Route::get('/blog2/{post:slug}', [BlogController::class, 'show2'])->name('blog2.show');

Route::middleware(['auth'])->group(function () {
    Route::get('force-password', [ForcePasswordChangeController::class, 'edit'])->name('force-password.edit');
    Route::put('force-password', [ForcePasswordChangeController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('force-password.update');
});

Route::middleware(['auth', 'password.change.required', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::middleware('admin')->group(function () {
        Route::get('admin/blog', [BlogPostController::class, 'index'])->name('admin.blog');
        Route::get('admin/blog/create', [BlogPostController::class, 'create'])->name('admin.blog.create');
        Route::post('admin/blog', [BlogPostController::class, 'store'])->name('admin.blog.store');
        Route::get('admin/blog/{post}/edit', [BlogPostController::class, 'edit'])->name('admin.blog.edit');
        Route::patch('admin/blog/{post}', [BlogPostController::class, 'update'])->name('admin.blog.update');
        Route::patch('admin/blog/{post}/published', [BlogPostController::class, 'togglePublished'])->name('admin.blog.published.toggle');
        Route::delete('admin/blog/{post}', [BlogPostController::class, 'destroy'])->name('admin.blog.destroy');
        Route::get('admin/tours', [AdminTourController::class, 'index'])->name('admin.tours');
        Route::get('admin/tours/create', [AdminTourController::class, 'create'])->name('admin.tours.create');
        Route::post('admin/tours', [AdminTourController::class, 'store'])->name('admin.tours.store');
        Route::get('admin/tours/{tour}/edit', [AdminTourController::class, 'edit'])->name('admin.tours.edit');
        Route::patch('admin/tours/{tour}', [AdminTourController::class, 'update'])->name('admin.tours.update');
        Route::patch('admin/tours/{tour}/hidden', [AdminTourController::class, 'toggleHidden'])->name('admin.tours.hidden.toggle');
        Route::delete('admin/tours/{tour}', [AdminTourController::class, 'destroy'])->name('admin.tours.destroy');
        Route::get('admin/tours/categories', [TourCategoryController::class, 'index'])->name('admin.tours.categories');
        Route::post('admin/tours/categories', [TourCategoryController::class, 'store'])->name('admin.tours.categories.store');
        Route::patch('admin/tours/categories/{category}', [TourCategoryController::class, 'update'])->name('admin.tours.categories.update');
        Route::delete('admin/tours/categories/{category}', [TourCategoryController::class, 'destroy'])->name('admin.tours.categories.destroy');
        Route::get('admin/tours/activities', [TourActivityController::class, 'index'])->name('admin.tours.activities');
        Route::post('admin/tours/activities', [TourActivityController::class, 'store'])->name('admin.tours.activities.store');
        Route::patch('admin/tours/activities/{activity}', [TourActivityController::class, 'update'])->name('admin.tours.activities.update');
        Route::delete('admin/tours/activities/{activity}', [TourActivityController::class, 'destroy'])->name('admin.tours.activities.destroy');
        Route::get('admin/destinations/countries', [DestinationCountryController::class, 'index'])->name('admin.destinations.countries');
        Route::get('admin/destinations/countries/create', [DestinationCountryController::class, 'create'])->name('admin.destinations.countries.create');
        Route::post('admin/destinations/countries', [DestinationCountryController::class, 'store'])->name('admin.destinations.countries.store');
        Route::get('admin/destinations/countries/{country}/edit', [DestinationCountryController::class, 'edit'])->name('admin.destinations.countries.edit');
        Route::patch('admin/destinations/countries/{country}', [DestinationCountryController::class, 'update'])->name('admin.destinations.countries.update');
        Route::delete('admin/destinations/countries/{country}', [DestinationCountryController::class, 'destroy'])->name('admin.destinations.countries.destroy');
        Route::get('admin/destinations/places', [DestinationPlaceController::class, 'index'])->name('admin.destinations.places');
        Route::get('admin/destinations/places/create', [DestinationPlaceController::class, 'create'])->name('admin.destinations.places.create');
        Route::post('admin/destinations/places', [DestinationPlaceController::class, 'store'])->name('admin.destinations.places.store');
        Route::get('admin/destinations/places/{place}/edit', [DestinationPlaceController::class, 'edit'])->name('admin.destinations.places.edit');
        Route::patch('admin/destinations/places/{place}', [DestinationPlaceController::class, 'update'])->name('admin.destinations.places.update');
        Route::delete('admin/destinations/places/{place}', [DestinationPlaceController::class, 'destroy'])->name('admin.destinations.places.destroy');
        Route::get('admin/form-submissions/contact', [FormSubmissionController::class, 'contact'])->name('admin.form-submissions.contact');
        Route::delete('admin/form-submissions/contact/{contact_submission}', [FormSubmissionController::class, 'destroyContactSubmission'])->name('admin.form-submissions.contact.destroy');
        Route::get('admin/form-submissions/tour', [FormSubmissionController::class, 'tour'])->name('admin.form-submissions.tour');
        Route::delete('admin/form-submissions/tour/{tour_enquiry}', [FormSubmissionController::class, 'destroyTourEnquiry'])->name('admin.form-submissions.tour.destroy');
    });
    Route::middleware('root')->group(function () {
        Route::get('root-access/prod-test', [ProdTestController::class, 'index'])->name('root-access.prod-test');
        Route::post('root-access/prod-test/email-test', [ProdTestController::class, 'sendEmailTest'])->name('root-access.prod-test.email-test.store');
        Route::get('root-access/site-settings', [SiteSettingsController::class, 'index'])->name('root-access.site-settings');
        Route::get('root-access/site-settings/general', [GeneralSiteSettingsController::class, 'index'])->name('root-access.site-settings.general');
        Route::put('root-access/site-settings/general', [GeneralSiteSettingsController::class, 'update'])->name('root-access.site-settings.general.update');
        Route::get('root-access/site-settings/logo', [LogoSiteSettingsController::class, 'index'])->name('root-access.site-settings.logo');
        Route::put('root-access/site-settings/logo', [LogoSiteSettingsController::class, 'update'])->name('root-access.site-settings.logo.update');
        Route::delete('root-access/site-settings/logo', [LogoSiteSettingsController::class, 'remove'])->name('root-access.site-settings.logo.remove');
        Route::get('root-access/site-settings/colors', [ColorSiteSettingsController::class, 'index'])->name('root-access.site-settings.colors');
        Route::put('root-access/site-settings/colors', [ColorSiteSettingsController::class, 'update'])->name('root-access.site-settings.colors.update');
        Route::get('root-access/site-settings/emails', [EmailSiteSettingsController::class, 'index'])->name('root-access.site-settings.emails');
        Route::put('root-access/site-settings/emails', [EmailSiteSettingsController::class, 'update'])->name('root-access.site-settings.emails.update');
        Route::get('root-access/users', [UserController::class, 'index'])->name('root-access.users');
        Route::post('root-access/users', [UserController::class, 'store'])->name('root-access.users.store');
        Route::patch('root-access/users/{user}', [UserController::class, 'update'])->name('root-access.users.update');
        Route::put('root-access/users/{user}/password', [UserController::class, 'updatePassword'])->name('root-access.users.password.update');
        Route::post('root-access/users/{user}/password-reset-link', [UserController::class, 'sendPasswordResetLink'])->name('root-access.users.password-reset-link.store');
        Route::delete('root-access/users/{user}', [UserController::class, 'destroy'])->name('root-access.users.destroy');
    });
});

require __DIR__.'/settings.php';
