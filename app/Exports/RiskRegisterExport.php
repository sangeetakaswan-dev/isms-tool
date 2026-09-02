<?php

namespace App\Exports;

use App\Models\RiskAssessment;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RiskRegisterExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new RiskSummarySheet(),
            new RiskDetailsSheet(),
            new RiskTreatmentSheet(),
        ];
    }
}

class RiskSummarySheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    public function collection()
    {
        $risks = RiskAssessment::all();
        $summary = [
            'Total Risks' => $risks->count(),
            'Critical' => $risks->where('risk_level', 'critical')->count(),
            'High' => $risks->where('risk_level', 'high')->count(),
            'Medium' => $risks->where('risk_level', 'medium')->count(),
            'Low' => $risks->where('risk_level', 'low')->count(),
        ];
        return collect($summary)->map(fn($value, $key) => ['Metric' => $key, 'Value' => $value]);
    }

    public function title(): string
    {
        return 'Summary';
    }
    public function headings(): array
    {
        return ['Metric', 'Value'];
    }
    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}

class RiskDetailsSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return RiskAssessment::with('asset')->get()->map(function ($risk) {
            return [
                'ID' => $risk->id,
                'Asset' => $risk->asset->name ?? '',
                'Threat' => $risk->threat,
                'Vulnerability' => $risk->vulnerability,
                'Likelihood' => $risk->likelihood,
                'Impact' => $risk->impact,
                'Score' => $risk->risk_score,
                'Level' => $risk->risk_level,
                'Treatment' => $risk->treatment,
                'Residual Score' => $risk->residual_score,
                'Residual Level' => $risk->residual_level,
                'Status' => $risk->status,
            ];
        });
    }

    public function title(): string
    {
        return 'Risk Register';
    }
    public function headings(): array
    {
        return ['ID', 'Asset', 'Threat', 'Vulnerability', 'Likelihood', 'Impact', 'Score', 'Level', 'Treatment', 'Residual Score', 'Residual Level', 'Status'];
    }
}

class RiskTreatmentSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return \App\Models\RiskTreatmentPlan::with('risk')->get()->map(function ($plan) {
            return [
                'Risk ID' => $plan->risk_assessment_id,
                'Action' => $plan->action,
                'Assigned To' => $plan->assigned_to,
                'Due Date' => $plan->due_date,
                'Status' => $plan->status,
                'Cost Estimate' => $plan->cost_estimate,
            ];
        });
    }

    public function title(): string
    {
        return 'Treatment Plans';
    }
    public function headings(): array
    {
        return ['Risk ID', 'Action', 'Assigned To', 'Due Date', 'Status', 'Cost Estimate'];
    }
}