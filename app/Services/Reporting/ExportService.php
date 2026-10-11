<?php

namespace App\Services\Reporting;

use App\DTOs\ReportData;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ExportService
{
    public function export(string $format, ReportData $data, int $tenantId, int $userId): Response
    {
        return match ($format) {
            'csv' => $this->csv($data),
            'pdf' => $this->pdf($data),
            'xlsx' => $this->excel($data),
            default => abort(400, "Unsupported format: {$format}"),
        };
    }

    protected function csv(ReportData $data): Response
    {
        $filename = $data->key . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $data->columns);
            foreach ($data->rows as $row) {
                fputcsv($out, array_values($row));
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function pdf(ReportData $data): Response
    {
        $pdf = Pdf::loadView('reports.pdf', ['report' => $data])
            ->setPaper('a4', 'landscape');

        return $pdf->download($data->key . '-' . now()->format('Ymd-His') . '.pdf');
    }

    protected function excel(ReportData $data): Response
    {
        return Excel::download(
            new ReportExport($data),
            $data->key . '-' . now()->format('Ymd-His') . '.xlsx'
        );
    }
}