<?php

use App\Http\Controllers\ImpersonationController;
use App\Livewire\Profile\TwoFactorAuthentication;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LegalPageController;

// Home route
Route::get('/', function () {
    return view('landing-page');
})->name('home');

// Dynamic CSS route
Route::get('/css/dynamic.css', [\App\Http\Controllers\DynamicCssController::class, 'index'])
    ->name('dynamic.css');

// Dashboard route (protected)
Route::middleware(['auth', 'verified', \App\Http\Middleware\EnsureTwoFactorChallengeIsComplete::class])->group(function () {

    // Stop impersonation route
    Route::get('/users/impersonate/stop', [ImpersonationController::class, 'stop'])->name('admin.users.impersonate.stop');

    // Two-factor authentication setup
    Route::get('/user/two-factor-authentication', TwoFactorAuthentication::class)->name('two-factor.setup');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        require __DIR__.'/admin.php';
    });
});

require __DIR__.'/auth.php';

Route::get('/language/{locale}', function ($locale, \App\Services\LocaleService $localeService) {
    if (in_array($locale, $localeService->getAvailableLocales())) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

Route::get('/legal/{slug}', [LegalPageController::class, 'show'])->name('legal.show');
