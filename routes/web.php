<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InsiderController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PulseController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StayController;
use App\Livewire\Actions\Logout;
use App\Livewire\Admin\Articles\Form as ArticleForm;
use App\Livewire\Admin\Articles\Index as ArticlesIndex;
use App\Livewire\Admin\Countries\Form as CountryForm;
use App\Livewire\Admin\Countries\Index as CountriesIndex;
use App\Livewire\Admin\Bookings\Form as BookingForm;
use App\Livewire\Admin\Bookings\Index as BookingsIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Destinations\Form as DestinationForm;
use App\Livewire\Admin\Destinations\Index as DestinationsIndex;
use App\Livewire\Admin\Enquiries\Create as EnquiryCreate;
use App\Livewire\Admin\Enquiries\Index as EnquiriesIndex;
use App\Livewire\Admin\Experiences\Form as ExperienceForm;
use App\Livewire\Admin\Experiences\Index as ExperiencesIndex;
use App\Livewire\Admin\Faqs\Form as FaqForm;
use App\Livewire\Admin\Faqs\Index as FaqsIndex;
use App\Livewire\Admin\Journeys\Form as JourneyForm;
use App\Livewire\Admin\Journeys\Index as JourneysIndex;
use App\Livewire\Admin\Pages\Form as PageForm;
use App\Livewire\Admin\Pages\Index as PagesIndex;
use App\Livewire\Admin\PulseItems\Form as PulseForm;
use App\Livewire\Admin\PulseItems\Index as PulseIndex;
use App\Livewire\Admin\Reviews\Form as ReviewForm;
use App\Livewire\Admin\Reviews\Index as ReviewsIndex;
use App\Livewire\Admin\Settings\Edit as SettingsEdit;
use App\Livewire\Admin\Stays\Form as StayForm;
use App\Livewire\Admin\Stays\Index as StaysIndex;
use App\Livewire\Admin\TeamMembers\Form as TeamForm;
use App\Livewire\Admin\TeamMembers\Index as TeamIndex;
use Illuminate\Support\Facades\Route;

Route::middleware('site.public')->group(function () {
    Route::get('/', HomeController::class)->name('home');

    Route::get('/journeys', [JourneyController::class, 'index'])->name('journeys.index');
    Route::get('/journeys/finder', [JourneyController::class, 'finder'])->name('journeys.finder');
    Route::get('/journeys/country/{country:slug}', [JourneyController::class, 'country'])->name('journeys.country');
    Route::get('/journeys/{journey:slug}', [JourneyController::class, 'show'])->name('journeys.show');

    Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
    Route::get('/destinations/{country}', [DestinationController::class, 'country'])->name('destinations.country');
    Route::get('/destinations/{country:slug}/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');

    Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');
    Route::get('/experiences/{experience:slug}', [ExperienceController::class, 'show'])->name('experiences.show');

    Route::get('/true-pulse', PulseController::class)->name('true-pulse');

    Route::get('/insiders', [InsiderController::class, 'index'])->name('insiders.index');
    Route::get('/insiders/{article:slug}', [InsiderController::class, 'show'])->name('insiders.show');

    Route::get('/plan-your-journey', [PageController::class, 'plan'])->name('plan');
    Route::redirect('/contact', '/plan-your-journey');

    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/about/our-people', [PageController::class, 'people'])->name('our-people');
    Route::get('/about/travel-with-a-reason', [PageController::class, 'reason'])->name('travel-with-a-reason');

    Route::get('/stays', [StayController::class, 'index'])->name('stays.index');
    Route::get('/stays/{stay:slug}', [StayController::class, 'show'])->name('stays.show');

    Route::get('/{page}', [PageController::class, 'legal'])
        ->whereIn('page', ['privacy', 'terms', 'cancellation', 'cookies'])
        ->name('legal');

    Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
});

Route::post('/logout', function (Logout $logout) {
    $logout();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::redirect('/dashboard', '/admin')->middleware(['auth', 'admin'])->name('dashboard');

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/favorites', [AccountController::class, 'favorites'])->name('favorites');
    Route::get('/requests', [AccountController::class, 'requests'])->name('requests');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
});

Route::redirect('/profile', '/account/profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/countries', CountriesIndex::class)->name('countries.index');
    Route::get('/countries/create', CountryForm::class)->name('countries.create');
    Route::get('/countries/{country}/edit', CountryForm::class)->name('countries.edit');
    Route::get('/destinations', DestinationsIndex::class)->name('destinations.index');
    Route::get('/destinations/create', DestinationForm::class)->name('destinations.create');
    Route::get('/destinations/{destination}/edit', DestinationForm::class)->name('destinations.edit');
    Route::get('/journeys', JourneysIndex::class)->name('journeys.index');
    Route::get('/journeys/create', JourneyForm::class)->name('journeys.create');
    Route::get('/journeys/{journey}/edit', JourneyForm::class)->name('journeys.edit');
    Route::get('/experiences', ExperiencesIndex::class)->name('experiences.index');
    Route::get('/experiences/create', ExperienceForm::class)->name('experiences.create');
    Route::get('/experiences/{experience}/edit', ExperienceForm::class)->name('experiences.edit');
    Route::get('/stays', StaysIndex::class)->name('stays.index');
    Route::get('/stays/create', StayForm::class)->name('stays.create');
    Route::get('/stays/{stay}/edit', StayForm::class)->name('stays.edit');
    Route::get('/team', TeamIndex::class)->name('team.index');
    Route::get('/team/create', TeamForm::class)->name('team.create');
    Route::get('/team/{member}/edit', TeamForm::class)->name('team.edit');
    Route::get('/reviews', ReviewsIndex::class)->name('reviews.index');
    Route::get('/reviews/create', ReviewForm::class)->name('reviews.create');
    Route::get('/reviews/{review}/edit', ReviewForm::class)->name('reviews.edit');
    Route::get('/articles', ArticlesIndex::class)->name('articles.index');
    Route::get('/articles/create', ArticleForm::class)->name('articles.create');
    Route::get('/articles/{article}/edit', ArticleForm::class)->name('articles.edit');
    Route::get('/pulse', PulseIndex::class)->name('pulse.index');
    Route::get('/pulse/create', PulseForm::class)->name('pulse.create');
    Route::get('/pulse/{item}/edit', PulseForm::class)->name('pulse.edit');
    Route::get('/faqs', FaqsIndex::class)->name('faqs.index');
    Route::get('/faqs/create', FaqForm::class)->name('faqs.create');
    Route::get('/faqs/{faq}/edit', FaqForm::class)->name('faqs.edit');
    Route::get('/pages', PagesIndex::class)->name('pages.index');
    Route::get('/pages/create', PageForm::class)->name('pages.create');
    Route::get('/pages/{page}/edit', PageForm::class)->name('pages.edit');
    Route::get('/enquiries', EnquiriesIndex::class)->name('enquiries.index');
    Route::get('/enquiries/create', EnquiryCreate::class)->name('enquiries.create');
    Route::get('/bookings', BookingsIndex::class)->name('bookings.index');
    Route::get('/bookings/create', BookingForm::class)->name('bookings.create');
    Route::get('/bookings/{booking}/edit', BookingForm::class)->name('bookings.edit');
    Route::get('/settings', SettingsEdit::class)->name('settings.edit');
});

require __DIR__.'/auth.php';
