<?php

namespace App\Exports;

use App\Models\Assessment;
use App\Services\Compliance\StatementOfApplicabilityService;
use Barryvdh\DomPDF\Facade\Pdf;

class SoAPdfExport
{
    public function __construct(
        private Assessment $assessment,
        private StatementOfApplicabilityService $soaService
    ) {}

    public function download()
    {
        $entries = $this->soaService->getSoAEntries($this->assessment);
        $pdf = Pdf::loadView('exports.soa_pdf', [
            'assessment' => $this->assessment,
            'entries' => $entries,
        ]);
        return $pdf->download('SoA_' . $this->assessment->id . '.pdf');
    }
}
