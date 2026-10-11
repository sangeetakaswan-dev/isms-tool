<?php

namespace App\Reports;

class DocumentComplianceReport extends AbstractReport
{
    public function key(): string { return 'document_compliance'; }
    public function title(): string { return 'Document Compliance'; }
    public function description(): string { return 'Document status and next review dates.'; }
    public function icon(): string { return '📁'; }

    public function columns(): array
    {
        return ['Title', 'Type', 'Status', 'Current Version', 'Review Date'];
    }

    public function rows(int $tenantId): array
    {
        return $this->repo->documents($tenantId)->map(fn ($d) => [
            'title' => $d->title,
            'type' => $d->document_type,
            'status' => $d->status,
            'version' => $d->current_version,
            'review_date' => optional($d->review_date)->format('Y-m-d'),
        ])->values()->toArray();
    }
}