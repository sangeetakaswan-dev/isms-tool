<?php

namespace App\Services\Risk;

use App\DTOs\RiskTreatmentData;
use App\Models\Risk;
use App\Models\RiskTreatmentPlan;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class RiskTreatmentService
{
    public function __construct(
        private readonly NotificationService $notifications,
    ) {}

    public function create(RiskTreatmentData $data): RiskTreatmentPlan
    {
        $plan = RiskTreatmentPlan::create($data->toArray());

        Log::info('Treatment plan created', ['plan_id' => $plan->id, 'risk_id' => $data->riskId]);

        // Risk status ko treated pe move karo
        $risk = Risk::find($data->riskId);
        if ($risk && $risk->status === 'assessed') {
            $risk->update(['status' => 'treated']);
        }

        if ($plan->assigned_to) {
            $this->notifications->notifyTreatmentAssigned($plan);
        }

        return $plan;
    }

    public function complete(RiskTreatmentPlan $plan, ?string $notes = null): RiskTreatmentPlan
    {
        $plan->update([
            'status'           => 'completed',
            'completed_at'     => now(),
            'completion_notes' => $notes,
        ]);

        // Agar sab treatments complete hain to risk → monitored
        $risk = $plan->risk;
        if ($risk && $risk->treatmentPlans()->where('status', '!=', 'completed')->count() === 0) {
            $risk->update(['status' => 'monitored', 'reviewed_at' => now()]);
        }

        return $plan->fresh();
    }

    public function forRisk(Risk $risk): Collection
    {
        return $risk->treatmentPlans()->with('assignedUser')->get();
    }

    public function delete(RiskTreatmentPlan $plan): bool
    {
        return $plan->delete();
    }
}