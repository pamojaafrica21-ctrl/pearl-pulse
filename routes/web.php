<?php

use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Actions\Logout;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Destinations\Form as DestinationForm;
use App\Livewire\Admin\Destinations\Index as DestinationsIndex;
use App\Livewire\Admin\Enquiries\Index as EnquiriesIndex;
use App\Livewire\Admin\Settings\Edit as SettingsEdit;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::post('/logout', function (Logout $logout) {
    $logout();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::redirect('/dashboard', '/admin')->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/destinations', DestinationsIndex::class)->name('destinations.index');
    Route::get('/destinations/create', DestinationForm::class)->name('destinations.create');
    Route::get('/destinations/{destination}/edit', DestinationForm::class)->name('destinations.edit');
    Route::get('/enquiries', EnquiriesIndex::class)->name('enquiries.index');
    Route::get('/settings', SettingsEdit::class)->name('settings.edit');
});

require __DIR__.'/auth.php';
