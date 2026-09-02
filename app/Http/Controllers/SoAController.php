<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\SoAEntry;
use App\Models\SoAVersion;
use App\Services\Compliance\StatementOfApplicabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SoAExcelExport;
use App\Exports\SoAPdfExport;

class SoAController extends Controller
{
    public function __construct(
        private StatementOfApplicabilityService $soaService
    ) {
    }

    public function index(Assessment $assessment)
    {
        $versions = $assessment->soaVersions()->latest()->get();
        $entries = $this->soaService->getSoAEntries($assessment);
        return view('soa.index', compact('assessment', 'versions', 'entries'));
    }

    public function generate(Assessment $assessment)
    {
        $this->soaService->generateSoA($assessment);
        return redirect()->route('soa.index', $assessment->id)
            ->with('success', 'SoA generated successfully');
    }

    public function show(SoAVersion $version)
    {
        $entries = collect($version->data_snapshot);
        return view('soa.show', compact('version', 'entries'));
    }

    public function approve($entry)
    {
        $entry = \App\Models\SoAEntry::findOrFail($entry);
        $this->soaService->approveEntry($entry, Auth::id());
        return back()->with('success', 'Entry approved');
    }
    public function bulkApprove(Assessment $assessment)
    {
        $this->soaService->bulkApprove($assessment, Auth::id());
        return back()->with('success', 'All entries approved');
    }

    public function createVersion(Assessment $assessment)
    {
        $this->soaService->createVersion($assessment, Auth::id(), request('version'));
        return back()->with('success', 'Version snapshot created');
    }

    public function compare(SoAVersion $v1, SoAVersion $v2)
    {
        $differences = $this->soaService->compareVersions($v1, $v2);
        return view('soa.compare', compact('v1', 'v2', 'differences'));
    }

    public function exportExcel(Assessment $assessment)
    {
        $export = new \App\Exports\SoAExcelExport($assessment, $this->soaService);
        return Excel::download($export, 'SoA_' . $assessment->id . '.xlsx');
    }

    public function exportPdf(Assessment $assessment)
    {
        $export = new \App\Exports\SoAPdfExport($assessment, $this->soaService);
        return $export->download();
    }
}
