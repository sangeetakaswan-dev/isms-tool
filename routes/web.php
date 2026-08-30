<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentResponseController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TreatmentPlanController;
use Illuminate\Support\Facades\Route;

// Home redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authenticated routes
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

    // ========== PHASE 2: ASSESSMENT ==========
    Route::resource('assessments', AssessmentController::class);

    // Assessment lifecycle
    Route::post('/assessments/{assessment}/start', [AssessmentController::class, 'start'])->name('assessments.start');
    Route::post('/assessments/{assessment}/complete', [AssessmentController::class, 'complete'])->name('assessments.complete');
    Route::post('/assessments/{assessment}/archive', [AssessmentController::class, 'archive'])->name('assessments.archive');

    // Assessment Responses
    Route::prefix('assessments/{assessment}/responses')->name('assessments.responses.')->group(function () {
        Route::get('/', [AssessmentResponseController::class, 'index'])->name('index');
        Route::get('/domain/{domain}', [AssessmentResponseController::class, 'showDomain'])->name('domain');
        Route::put('/{response}', [AssessmentResponseController::class, 'update'])->name('update');
        Route::post('/bulk-update', [AssessmentResponseController::class, 'bulkUpdate'])->name('bulk-update');
        Route::post('/bulk-assign', [AssessmentResponseController::class, 'bulkAssign'])->name('bulk-assign');
        Route::post('/auto-assign', [AssessmentResponseController::class, 'autoAssign'])->name('auto-assign');
        Route::post('/{response}/evidence', [AssessmentResponseController::class, 'uploadEvidence'])->name('evidence');
    });

    // ========== PHASE 3: RISK MANAGEMENT ==========
    // Assets
    Route::resource('assets', AssetController::class);

    // Risks
    Route::resource('risks', RiskController::class);
    Route::get('risks/heatmap', [RiskController::class, 'heatmap'])->name('risks.heatmap');
    Route::get('risks/export/excel', [RiskController::class, 'exportExcel'])->name('risks.export.excel');

    // Treatment Plans
    Route::resource('treatments', TreatmentPlanController::class);
});

// Auth routes (Breeze)
require __DIR__ . '/auth.php';