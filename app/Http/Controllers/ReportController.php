<?php

namespace App\Http\Controllers;

use App\Repositories\Reporting\Contracts\ReportRepositoryInterface;
use App\Services\Reporting\ExportService;
use App\Services\Reporting\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $service,
        private ExportService $exporter,
        private ReportRepositoryInterface $repo,
    ) {
    }

    protected function currentTenantId(Request $request): int
    {
        $user = $request->user();
        $tenant = $user->currentTenant ?? $user->tenants()->first();

        abort_unless($tenant, 403, 'No active tenant.');

        return $tenant->id;
    }

    public function index()
    {
        return view('reports.index', [
            'types' => $this->service->availableTypes(),
        ]);
    }

    public function show(Request $request, string $type)
    {
        $tenantId = $this->currentTenantId($request);

        $report = $this->service->generate($type, $tenantId);

        return view('reports.show', [
            'report' => $report,
            'types' => $this->service->availableTypes(),
        ]);
    }

    public function export(Request $request, string $type, string $format)
    {
        $tenantId = $this->currentTenantId($request);

        $report = $this->service->generate($type, $tenantId);

        $this->repo->storeGeneratedReport([
            'tenant_id' => $tenantId,
            'user_id' => $request->user()->id,
            'report_type' => $type,
            'format' => $format,
            'parameters' => [],
        ]);

        return $this->exporter->export($format, $report, $tenantId, $request->user()->id);
    }

    public function history()
    {
        $reports = \App\Models\GeneratedReport::where('tenant_id', auth()->user()->current_tenant_id)
            ->latest()
            ->paginate(15);

        return view('reports.history', compact('reports'));
    }
}