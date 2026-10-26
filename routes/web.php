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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RiskRegisterController;
use App\Http\Controllers\RiskTreatmentController;

/*
|--------------------------------------------------------------------------
| Landing Page & Home
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Login Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // =================================================================
    // 1. DASHBOARD
    // =================================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/risk', function () {
        return view('dashboard.risk');
    })->name('dashboard.risk');
    Route::get('/dashboard/metrics', [DashboardController::class, 'metrics'])->name('dashboard.metrics');

    // =================================================================
    // 2. PROFILE MANAGEMENT
    // =================================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =================================================================
    // 3. TENANT MANAGEMENT
    // =================================================================
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('index');
        Route::get('/setup', [TenantController::class, 'create'])->name('setup');
        Route::post('/', [TenantController::class, 'store'])->name('store');
        Route::get('/{tenant}', [TenantController::class, 'show'])->name('show');
        Route::get('/{tenant}/edit', [TenantController::class, 'edit'])->name('edit');
        Route::put('/{tenant}', [TenantController::class, 'update'])->name('update');
        Route::post('/{tenant}/switch', [TenantController::class, 'switchTenant'])->name('switch');
    });

    // =================================================================
    // 4. INVITATIONS (Nested under tenants)
    // =================================================================
    Route::prefix('tenants/{tenant}/invitations')->name('invitations.')->group(function () {
        Route::get('/', [InvitationController::class, 'index'])->name('index');
        Route::post('/', [InvitationController::class, 'store'])->name('store');
        Route::delete('/{invitation}', [InvitationController::class, 'destroy'])->name('destroy');
    });

    // =================================================================
    // 5. ASSESSMENT MANAGEMENT
    // =================================================================
    Route::resource('assessments', AssessmentController::class);

    Route::post('/assessments/{assessment}/start', [AssessmentController::class, 'start'])->name('assessments.start');
    Route::post('/assessments/{assessment}/complete', [AssessmentController::class, 'complete'])->name('assessments.complete');
    Route::post('/assessments/{assessment}/archive', [AssessmentController::class, 'archive'])->name('assessments.archive');

    // =================================================================
    // 6. ASSESSMENT RESPONSES (Controls Evaluation)
    // =================================================================
    Route::prefix('assessments/{assessment}/responses')->name('assessments.responses.')->group(function () {
        Route::get('/', [AssessmentResponseController::class, 'index'])->name('index');
        Route::get('/domain/{domain}', [AssessmentResponseController::class, 'showDomain'])->name('domain');
        Route::put('/{response}', [AssessmentResponseController::class, 'update'])->name('update');
        Route::post('/bulk-update', [AssessmentResponseController::class, 'bulkUpdate'])->name('bulk-update');
        Route::post('/bulk-assign', [AssessmentResponseController::class, 'bulkAssign'])->name('bulk-assign');
        Route::post('/auto-assign', [AssessmentResponseController::class, 'autoAssign'])->name('auto-assign');
        Route::post('/{response}/evidence', [AssessmentResponseController::class, 'uploadEvidence'])->name('evidence');
    });

    // =================================================================
    // 7. STATEMENT OF APPLICABILITY (SoA) — Nested under assessments
    // =================================================================
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

    // Direct SoA redirect (from sidebar) — FIXED: now inside auth
    Route::get('/soa', function () {
        $assessment = \App\Models\Assessment::where('tenant_id', auth()->user()->current_tenant_id)->latest()->first();
        if ($assessment) {
            return redirect()->route('soa.index', $assessment);
        }
        return redirect()->route('assessments.index')->with('error', 'Please create an assessment first to generate SoA.');
    })->name('soa.direct');

    // =================================================================
    // 8. ASSETS
    // =================================================================
    Route::resource('assets', AssetController::class);

    // =================================================================
    // 9. DOCUMENTS
    // =================================================================
    Route::resource('documents', DocumentController::class);
    Route::post('documents/{document}/link-control/{control}', [DocumentController::class, 'linkControl'])->name('documents.link-control');
    Route::post('documents/{document}/submit-review', [DocumentReviewController::class, 'submitForReview'])->name('documents.submit-review');
    Route::post('documents/{document}/approve', [DocumentReviewController::class, 'approve'])->name('documents.approve');
    Route::post('documents/{document}/reject', [DocumentReviewController::class, 'reject'])->name('documents.reject');
    Route::post('documents/{document}/request-changes', [DocumentReviewController::class, 'requestChanges'])->name('documents.request-changes');
    Route::post('documents/{document}/version', [DocumentVersionController::class, 'store'])->name('documents.versions.store');

    // =================================================================
    // 10. REPORTS — FIXED: moved inside auth
    // =================================================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/history', [ReportController::class, 'history'])->name('history');
        Route::get('/{type}', [ReportController::class, 'show'])->name('show');
        Route::get('/{type}/export/{format}', [ReportController::class, 'export'])
            ->whereIn('format', ['csv', 'pdf', 'xlsx'])
            ->name('export');
    });

    // =================================================================
    // 11. PHASE 7: RISK REGISTER (Primary Risk System)
    // =================================================================
    // Note: Phase 3 ka `Route::resource('risks')` hata diya hai kyunki
    // Phase 7 wahi functionality better UI ke saath provide karta hai.
    // Lekin Phase 3 ke `risks.heatmap` aur `risks.export.excel` NEECHE preserved hain.
    Route::middleware(['tenant.selected'])->group(function () {

        // Risk Register CRUD (Phase 7)
        Route::get('/risks', [RiskRegisterController::class, 'index'])->name('risks.index');
        Route::get('/risks/create', [RiskRegisterController::class, 'create'])->name('risks.create');
        Route::post('/risks', [RiskRegisterController::class, 'store'])->name('risks.store');
        Route::get('/risks/heatmap/data', [RiskRegisterController::class, 'heatmapData'])->name('risks.heatmap.data');
        Route::get('/risks/{risk}', [RiskRegisterController::class, 'show'])->name('risks.show');
        Route::get('/risks/{risk}/edit', [RiskRegisterController::class, 'edit'])->name('risks.edit');
        Route::put('/risks/{risk}', [RiskRegisterController::class, 'update'])->name('risks.update');
        Route::delete('/risks/{risk}', [RiskRegisterController::class, 'destroy'])->name('risks.destroy');

        // Phase 3 Legacy Risk routes (preserved — heatmap view & excel export)
        Route::get('/risks/heatmap', [RiskController::class, 'heatmap'])->name('risks.heatmap');
        Route::get('/risks/export/excel', [RiskController::class, 'exportExcel'])->name('risks.export.excel');

        // Treatment Plans (Phase 7 — for Risk Register)
        Route::get('/risks/{risk}/treatments/create', [RiskTreatmentController::class, 'create'])->name('risks.treatments.create');
        Route::post('/risks/{risk}/treatments', [RiskTreatmentController::class, 'store'])->name('risks.treatments.store');
        Route::get('/treatments/{plan}/edit', [RiskTreatmentController::class, 'edit'])->name('risks.treatments.edit');
        Route::put('/treatments/{plan}', [RiskTreatmentController::class, 'update'])->name('risks.treatments.update');
        Route::post('/treatments/{plan}/complete', [RiskTreatmentController::class, 'complete'])->name('risks.treatments.complete');
        Route::delete('/treatments/{plan}', [RiskTreatmentController::class, 'destroy'])->name('risks.treatments.destroy');

        // Phase 3 Legacy Treatment Plan (preserved — only index & show to avoid URL conflicts)
        Route::resource('treatments', TreatmentPlanController::class)->only(['index', 'show']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';