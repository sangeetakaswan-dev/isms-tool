<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tenant Management
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('index');
        Route::get('/setup', [TenantController::class, 'create'])->name('setup');
        Route::post('/', [TenantController::class, 'store'])->name('store');
        Route::get('/{tenant}', [TenantController::class, 'show'])->name('show');
        Route::get('/{tenant}/edit', [TenantController::class, 'edit'])->name('edit');
        Route::put('/{tenant}', [TenantController::class, 'update'])->name('update');
        Route::post('/{tenant}/switch', [TenantController::class, 'switchTenant'])->name('switch');
    });

    // Invitations
    Route::prefix('tenants/{tenant}/invitations')->name('invitations.')->group(function () {
        Route::get('/', [InvitationController::class, 'index'])->name('index');
        Route::post('/', [InvitationController::class, 'store'])->name('store');
        Route::delete('/{invitation}', [InvitationController::class, 'destroy'])->name('destroy');
    });
});

// Assessment Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Assessment CRUD
    Route::resource('assessments', AssessmentController::class);

    // Assessment lifecycle
    Route::post('/assessments/{assessment}/start', [AssessmentController::class, 'start'])
        ->name('assessments.start');

    Route::post('/assessments/{assessment}/complete', [AssessmentController::class, 'complete'])
        ->name('assessments.complete');

    Route::post('/assessments/{assessment}/archive', [AssessmentController::class, 'archive'])
        ->name('assessments.archive');

    // Assessment Responses
    Route::prefix('assessments/{assessment}/responses')->group(function () {
        Route::get('/', [AssessmentResponseController::class, 'index'])
            ->name('assessments.responses.index');

        Route::get('/domain/{domain}', [AssessmentResponseController::class, 'showDomain'])
            ->name('assessments.responses.domain');

        Route::put('/{response}', [AssessmentResponseController::class, 'update'])
            ->name('assessments.responses.update');

        Route::post('/bulk-update', [AssessmentResponseController::class, 'bulkUpdate'])
            ->name('assessments.responses.bulk-update');

        Route::post('/bulk-assign', [AssessmentResponseController::class, 'bulkAssign'])
            ->name('assessments.responses.bulk-assign');

        Route::post('/auto-assign', [AssessmentResponseController::class, 'autoAssign'])
            ->name('assessments.responses.auto-assign');

        Route::post('/{response}/evidence', [AssessmentResponseController::class, 'uploadEvidence'])
            ->name('assessments.responses.evidence');
    });
});

require __DIR__ . '/auth.php';