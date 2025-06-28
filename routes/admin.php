<?php

use App\Http\Controllers\ImpersonationController;
use App\Livewire\Admin\ActivityLogManagement;
use App\Livewire\Admin\AnalyticsDashboard;
use App\Livewire\Admin\AttachmentManagement;
use App\Livewire\Admin\Legal\EditLegalPage;
use App\Livewire\Admin\Legal\LegalPageManagement;
use App\Livewire\Admin\Roles\Index as RoleIndex;
use App\Livewire\Admin\Roles\ManageRole;
use App\Livewire\Admin\SettingsManagement;
use App\Livewire\Admin\Taxonomies\Index as TaxonomyIndex;
use App\Livewire\Admin\Taxonomies\ManageTaxonomy;
use App\Livewire\Admin\Terms\Index as TermIndex;
use App\Livewire\Admin\Terms\ManageTerm;
use App\Livewire\Admin\Users\Index as UserIndex;
use App\Livewire\Admin\Users\ManageUser;
use App\Livewire\Admin\UserProfile;
use App\Livewire\Admin\NotificationManagement;
use Illuminate\Support\Facades\Route;

Route::get('/profile', UserProfile::class)->name('profile');

Route::middleware(['permission:view-dashboard'])->group(function () {
    Route::get('/', AnalyticsDashboard::class)->name('dashboard');
});

// Role Management
Route::middleware(['permission:view-roles'])->group(function () {
    Route::get('/roles', RoleIndex::class)->name('roles.index');
    Route::get('/roles/create', ManageRole::class)->name('roles.create')->middleware('permission:create-roles');
    Route::get('/roles/{role}/edit', ManageRole::class)->name('roles.edit')->middleware('permission:edit-roles');
});

// Taxonomy Management
Route::middleware(['permission:view-taxonomies'])->group(function () {
    Route::get('/taxonomies', TaxonomyIndex::class)->name('taxonomies.index');
    Route::get('/taxonomies/create', ManageTaxonomy::class)->name('taxonomies.create')->middleware('permission:create-taxonomies');
    Route::get('/taxonomies/{taxonomy}/edit', ManageTaxonomy::class)->name('taxonomies.edit')->middleware('permission:edit-taxonomies');
    Route::middleware(['permission:view-terms'])->group(function () {
        Route::get('/taxonomies/{taxonomy}/terms', TermIndex::class)->name('taxonomies.terms.index');
        Route::get('/taxonomies/{taxonomy}/terms/create', ManageTerm::class)->name('taxonomies.terms.create')->middleware('permission:create-terms');
        Route::get('/taxonomies/{taxonomy}/terms/{term}/edit', ManageTerm::class)->name('taxonomies.terms.edit')->middleware('permission:edit-terms');
    });
});

Route::get('/settings', SettingsManagement::class)->name('settings')->middleware('permission:view-settings');
Route::get('/attachments', AttachmentManagement::class)->name('attachments')->middleware('permission:view-attachments');
Route::get('/activity-logs', ActivityLogManagement::class)->name('activity-logs')->middleware('permission:view-activity-logs');
Route::middleware(['permission:view-notifications'])->group(function () {
    Route::get('/notifications', NotificationManagement::class)->name('notifications');
});

// Legal
Route::middleware(['permission:view-legal-pages'])->group(function () {
    Route::get('/legal', LegalPageManagement::class)->name('legal.index');
    Route::get('/legal/create', EditLegalPage::class)->name('legal.create')->middleware('permission:create-legal-pages');
    Route::get('/legal/{legalPage}/edit', EditLegalPage::class)->name('legal.edit')->middleware('permission:edit-legal-pages');
});

// A/B Testing Management
Route::prefix('experiments')->name('experiments.')->middleware(['permission:view-experiments'])->group(function () {
    Route::get('/', \App\Livewire\Admin\Experiments\Index::class)->name('index');
    Route::get('/create', \App\Livewire\Admin\Experiments\ManageExperiment::class)->name('create')->middleware('permission:create-experiments');
    Route::get('/{experiment}/edit', \App\Livewire\Admin\Experiments\ManageExperiment::class)->name('edit')->middleware('permission:edit-experiments');
    Route::get('/{experiment}', \App\Livewire\Admin\Experiments\ShowExperiment::class)->name('show');
});

// Submissions Management
Route::prefix('submissions')->name('submissions.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Submissions\Index::class)->name('index');
    Route::get('/{submission}', \App\Livewire\Admin\Submissions\Show::class)->name('show');
});

// User Management
Route::prefix('users')->name('users.')->middleware(['permission:view-users'])->group(function () {
    Route::get('/', UserIndex::class)->name('index');
    Route::get('/create', ManageUser::class)->name('create')->middleware('permission:create-users');
    Route::get('/{user}/edit', ManageUser::class)->name('edit')->middleware('permission:edit-users');
    Route::get('/{user}/impersonate', [ImpersonationController::class, 'start'])->name('impersonate');
});

// Testimonials Management
Route::prefix('testimonials')->name('testimonials.')->middleware(['permission:view-testimonials'])->group(function () {
    Route::get('/', \App\Livewire\Admin\Testimonials\Index::class)->name('index');
    Route::get('/create', \App\Livewire\Admin\Testimonials\ManageTestimonial::class)->name('create')->middleware('permission:create-testimonials');
    Route::get('/{testimonial}/edit', \App\Livewire\Admin\Testimonials\ManageTestimonial::class)->name('edit')->middleware('permission:edit-testimonials');
}); 