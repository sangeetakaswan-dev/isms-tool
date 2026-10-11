<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Control;
use App\Models\Domain;
use App\Services\Reporting\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $tenant = $user->currentTenant ?? $user->tenants()->first();

        abort_unless($tenant, 403, 'No active tenant. Please select a tenant first.');

        $tenantId = $tenant->id;

        $stats = [
            'total_domains'         => Domain::count(),
            'total_controls'        => Control::active()->count(),
            'annex_a_controls'      => Control::active()->annexA()->count(),
            'main_clause_controls'  => Control::active()->mainClause()->count(),
            'active_assessments'    => Assessment::where('tenant_id', $tenantId)->active()->count(),
            'completed_assessments' => Assessment::where('tenant_id', $tenantId)->byStatus('completed')->count(),
        ];

        $domains = Domain::ordered()
            ->withCount(['controls' => fn ($q) => $q->active()])
            ->get();

        $recentAssessments = Assessment::where('tenant_id', $tenantId)
            ->with('leadAssessor')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $metrics = $this->dashboardService->getDashboardData($tenantId);

        return view('dashboard', compact(
            'stats',
            'domains',
            'recentAssessments',
            'metrics'
        ));
    }

    public function metrics()
    {
        $user = auth()->user();
        $tenant = $user->currentTenant ?? $user->tenants()->first();

        abort_unless($tenant, 403, 'No active tenant.');

        return response()->json(
            $this->dashboardService->getDashboardData($tenant->id)->toArray()
        );
    }
}