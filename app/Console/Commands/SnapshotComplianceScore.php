<?php

namespace App\Console\Commands;

use App\Jobs\SnapshotComplianceScoreJob;
use Illuminate\Console\Command;

class SnapshotComplianceScore extends Command
{
    protected $signature = 'compliance:snapshot';
    protected $description = 'Create daily compliance score snapshots for all tenants';

    public function handle(): int
    {
        SnapshotComplianceScoreJob::dispatchSync();
        $this->info('Snapshots created.');
        return self::SUCCESS;
    }
}