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
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentReviewController;
use App\Http\Controllers\DocumentVersionController;
use App\Http\Controllers\SoAController;

// Note: SoAController is used in SOA routes but not imported.
// Add: use App\Http\Controllers\SoAController;

/*
|--------------------------------------------------------------------------
| Home Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/risk', function () {
        return view('dashboard.risk');
    })->name('dashboard.risk');

    /*
    |----------------------------------------------------------------------
    | Profile Management
    |----------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |----------------------------------------------------------------------
    | Tenant Management
    |----------------------------------------------------------------------
    */
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('index');
        Route::get('/setup', [TenantController::class, 'create'])->name('setup');
        Route::post('/', [TenantController::class, 'store'])->name('store');
        Route::get('/{tenant}', [TenantController::class, 'show'])->name('show');
        Route::get('/{tenant}/edit', [TenantController::class, 'edit'])->name('edit');
        Route::put('/{tenant}', [TenantController::class, 'update'])->name('update');
        Route::post('/{tenant}/switch', [TenantController::class, 'switchTenant'])->name('switch');
    });

    /*
    |----------------------------------------------------------------------
    | Invitations (nested under tenants)
    |----------------------------------------------------------------------
    */
    Route::prefix('tenants/{tenant}/invitations')->name('invitations.')->group(function () {
        Route::get('/', [InvitationController::class, 'index'])->name('index');
        Route::post('/', [InvitationController::class, 'store'])->name('store');
        Route::delete('/{invitation}', [InvitationController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Assessment Management
    |----------------------------------------------------------------------
    */
    Route::resource('assessments', AssessmentController::class);

    // Assessment lifecycle actions
    Route::post('/assessments/{assessment}/start', [AssessmentController::class, 'start'])->name('assessments.start');
    Route::post('/assessments/{assessment}/complete', [AssessmentController::class, 'complete'])->name('assessments.complete');
    Route::post('/assessments/{assessment}/archive', [AssessmentController::class, 'archive'])->name('assessments.archive');

    /*
    |----------------------------------------------------------------------
    | Statement of Applicability (SOA) – nested under assessments
    |----------------------------------------------------------------------
    */
    Route::prefix('assessments/{assessment}/soa')->group(function () {
        Route::get('/', [SoAController::class, 'index'])->name('soa.index');
        Route::post('/generate', [SoAController::class, 'generate'])->name('soa.generate');
        Route::post('/approve/{entry}', [SoAController::class, 'approve'])->name('soa.approve');
        Route::post('/bulk-approve', [SoAController::class, 'bulkApprove'])->name('soa.bulkApprove');
        Route::post('/version', [SoAController::class, 'createVersion'])->name('soa.version');
        Route::get('/version/{version}', [SoAController::class, 'show'])->name('soa.version.show');
        Route::get('/compare/{v1}/{v2}', [SoAController::class, 'compare'])->name('soa.compare');
        Route::get('/export-excel', [SoAController::class, 'exportExcel'])->name('soa.export.excel');
        Route::get('/export-pdf', [SoAController::class, 'exportPdf'])->name('soa.export.pdf');
    });

    /*
    |----------------------------------------------------------------------
    | Assessment Responses
    |----------------------------------------------------------------------
    */
    Route::prefix('assessments/{assessment}/responses')->name('assessments.responses.')->group(function () {
        Route::get('/', [AssessmentResponseController::class, 'index'])->name('index');
        Route::get('/domain/{domain}', [AssessmentResponseController::class, 'showDomain'])->name('domain');
        Route::put('/{response}', [AssessmentResponseController::class, 'update'])->name('update');
        Route::post('/bulk-update', [AssessmentResponseController::class, 'bulkUpdate'])->name('bulk-update');
        Route::post('/bulk-assign', [AssessmentResponseController::class, 'bulkAssign'])->name('bulk-assign');
        Route::post('/auto-assign', [AssessmentResponseController::class, 'autoAssign'])->name('auto-assign');
        Route::post('/{response}/evidence', [AssessmentResponseController::class, 'uploadEvidence'])->name('evidence');
    });

    /*
    |----------------------------------------------------------------------
    | Risk Management
    |----------------------------------------------------------------------
    */
    // Assets
    Route::resource('assets', AssetController::class);

    // Risks
    Route::resource('risks', RiskController::class);
    Route::get('risks/heatmap', [RiskController::class, 'heatmap'])->name('risks.heatmap');
    Route::get('risks/export/excel', [RiskController::class, 'exportExcel'])->name('risks.export.excel');

    // Treatment Plans
    Route::resource('treatments', TreatmentPlanController::class);

    Route::resource('documents', DocumentController::class);
    Route::post('documents/{document}/link-control/{control}', [DocumentController::class, 'linkControl'])->name('documents.link-control');
    Route::post('documents/{document}/submit-review', [DocumentReviewController::class, 'submitForReview'])->name('documents.submit-review');
    Route::post('documents/{document}/approve', [DocumentReviewController::class, 'approve'])->name('documents.approve');
    Route::post('documents/{document}/reject', [DocumentReviewController::class, 'reject'])->name('documents.reject');
    Route::post('documents/{document}/request-changes', [DocumentReviewController::class, 'requestChanges'])->name('documents.request-changes');
    Route::post('documents/{document}/version', [DocumentVersionController::class, 'store'])->name('documents.versions.store');
});


/*
|--------------------------------------------------------------------------
| Authentication Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';