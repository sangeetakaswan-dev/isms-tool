<?php

use App\Jobs\PruneReportSnapshotsJob;
use App\Jobs\SendTreatmentDueRemindersJob;
use App\Jobs\SnapshotComplianceScoreJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new SnapshotComplianceScoreJob)->dailyAt('02:00');
Schedule::job(new SendTreatmentDueRemindersJob)->dailyAt('08:00');
Schedule::job(new PruneReportSnapshotsJob)->weeklyOn(0, '03:00');