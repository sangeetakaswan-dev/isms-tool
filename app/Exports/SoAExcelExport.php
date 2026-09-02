<?php

namespace App\Exports;

use App\Models\Assessment;
use App\Services\Compliance\StatementOfApplicabilityService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SoAExcelExport implements FromCollection, WithHeadings, WithTitle
{
    public function __construct(
        private Assessment $assessment,
        private StatementOfApplicabilityService $soaService
    ) {}

    public function collection()
    {
        $entries = $this->soaService->getSoAEntries($this->assessment);
        return $entries->map(function ($entry) {
            return [
                'Control ID' => $entry->control->control_id,
                'Title' => $entry->control->title,
                'Applicable' => $entry->applicable ? 'Yes' : 'No',
                'Justification' => $entry->justification,
                'Implementation Status' => $entry->implementation_status,
                'Exclusion Reason' => $entry->exclusion_reason,
                'Approved By' => optional($entry->approver)->name,
            ];
        });
    }

    public function headings(): array
    {
        return ['Control ID', 'Title', 'Applicable', 'Justification', 'Implementation Status', 'Exclusion Reason', 'Approved By'];
    }

    public function title(): string
    {
        return 'SoA';
    }
}
