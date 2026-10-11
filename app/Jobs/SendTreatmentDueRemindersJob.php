<?php

namespace App\Jobs;

use App\Models\RiskTreatmentPlan;
use App\Services\Notification\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTreatmentDueRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(NotificationService $notifications): void
    {
        $overdue = RiskTreatmentPlan::where('status', '!=', 'completed')
            ->whereDate('due_date', '<', now())
            ->whereNotNull('assigned_to')
            ->get();

        foreach ($overdue as $plan) {
            $notifications->notifyTreatmentOverdue($plan);
        }
    }
}