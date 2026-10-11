<?php

namespace App\Services\Notification;

use App\Models\Risk;
use App\Models\RiskTreatmentPlan;
use App\Models\User;
use App\Notifications\RiskAssignedNotification;
use App\Notifications\TreatmentDueNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function notifyRiskAssigned(Risk $risk): void
    {
        $owner = User::find($risk->owner_id);
        if (!$owner) return;

        Notification::send($owner, new RiskAssignedNotification($risk));
    }

    public function notifyTreatmentAssigned(RiskTreatmentPlan $plan): void
    {
        $user = User::find($plan->assigned_to);
        if (!$user) return;

        Notification::send($user, new TreatmentDueNotification($plan));
    }

    public function notifyTreatmentOverdue(RiskTreatmentPlan $plan): void
    {
        $user = User::find($plan->assigned_to);
        if (!$user) return;

        Notification::send($user, new TreatmentDueNotification($plan, overdue: true));
    }
}