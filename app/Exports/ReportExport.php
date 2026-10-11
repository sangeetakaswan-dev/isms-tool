<?php

namespace App\Exports;

use App\DTOs\ReportData;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportExport implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private ReportData $data) {}

    public function array(): array
    {
        return array_map('array_values', $this->data->rows);
    }

    public function headings(): array
    {
        return $this->data->columns;
    }

    public function title(): string
    {
        return $this->data->title;
    }
}