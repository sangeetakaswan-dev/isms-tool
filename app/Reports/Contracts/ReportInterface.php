<?php

namespace App\Reports\Contracts;

interface ReportInterface
{
    public function key(): string;
    public function title(): string;
    public function description(): string;
    public function icon(): string;
    public function columns(): array;
    public function rows(int $tenantId): array;
    public function summary(int $tenantId): array;
}