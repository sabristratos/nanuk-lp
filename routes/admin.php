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

// Impersonation routes
Route::get('/users/{user}/impersonate', [ImpersonationController::class, 'start'])->name('users.impersonate');
Route::get('/users/impersonate/stop', [ImpersonationController::class, 'stop'])->name('users.impersonate.stop');

Route::get('/profile', UserProfile::class)->name('profile');

Route::middleware(['permission:view-dashboard'])->group(function () {
    Route::get('/', AnalyticsDashboard::class)->name('dashboard');
});

// User Management
Route::middleware(['permission:view-users'])->group(function () {
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/users/create', ManageUser::class)->name('users.create')->middleware('permission:create-users');
    Route::get('/users/{user}/edit', ManageUser::class)->name('users.edit')->middleware('permission:edit-users');
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
    // Add create/delete notification routes here if they exist and need protection
});

// Legal
Route::middleware(['permission:view-legal-pages'])->group(function () {
    Route::get('/legal', LegalPageManagement::class)->name('legal.index');
    Route::get('/legal/create', EditLegalPage::class)->name('legal.create')->middleware('permission:create-legal-pages');
    Route::get('/legal/{legalPage}/edit', EditLegalPage::class)->name('legal.edit')->middleware('permission:edit-legal-pages');
});

// A/B Testing Management
Route::middleware(['permission:view-experiments'])->group(function () {
    Route::get('/experiments', \App\Livewire\Admin\Experiments\Index::class)->name('experiments.index');
    Route::get('/experiments/create', \App\Livewire\Admin\Experiments\ManageExperiment::class)->name('experiments.create')->middleware('permission:create-experiments');
    Route::get('/experiments/{experiment}/edit', \App\Livewire\Admin\Experiments\ManageExperiment::class)->name('experiments.edit')->middleware('permission:edit-experiments');
    Route::get('/experiments/{experiment}', \App\Livewire\Admin\Experiments\ShowExperiment::class)->name('experiments.show')->middleware('permission:view-experiments');
});

// Add more admin routes here as needed 

// A/B Testing
Route::prefix('experiments')->name('experiments.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Experiments\Index::class)->name('index');
    Route::get('/create', \App\Livewire\Admin\Experiments\ManageExperiment::class)->name('create');
    Route::get('/{experiment}/edit', \App\Livewire\Admin\Experiments\ManageExperiment::class)->name('edit');
    Route::get('/{experiment}', \App\Livewire\Admin\Experiments\ShowExperiment::class)->name('show');
});
Route::prefix('submissions')->name('submissions.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Submissions\Index::class)->name('index');
    Route::get('/{result}', \App\Livewire\Admin\Submissions\Show::class)->name('show');
});

// User Management
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Users\Index::class)->name('index');
    Route::get('/create', \App\Livewire\Admin\Users\ManageUser::class)->name('create');
    Route::get('/{user}/edit', \App\Livewire\Admin\Users\ManageUser::class)->name('edit');
}); 