<?php

namespace App\Reports;

use App\Reports\Contracts\ReportInterface;
use App\Repositories\Reporting\Contracts\ReportRepositoryInterface;

abstract class AbstractReport implements ReportInterface
{
    public function __construct(protected ReportRepositoryInterface $repo) {}

    public function icon(): string
    {
        return '📄';
    }

    public function summary(int $tenantId): array
    {
        return [];
    }
}