<?php

namespace Database\Seeders;

use App\Models\RiskTreatmentPlan;
use Illuminate\Database\Seeder;

class TreatmentPlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            ['risk_assessment_id' => 1, 'action' => 'Implement MFA', 'description' => 'Enable multi-factor authentication for all users', 'assigned_to' => 1, 'due_date' => '2026-09-15', 'status' => 'pending'],
            ['risk_assessment_id' => 2, 'action' => 'Upgrade Network Equipment', 'description' => 'Add redundancy to network infrastructure', 'assigned_to' => 1, 'due_date' => '2026-10-01', 'status' => 'in_progress'],
            ['risk_assessment_id' => 3, 'action' => 'Device Encryption', 'description' => 'Enable full disk encryption on all laptops', 'assigned_to' => 1, 'due_date' => '2026-08-30', 'status' => 'completed'],
            ['risk_assessment_id' => 4, 'action' => 'Multi-Cloud Strategy', 'description' => 'Use multiple cloud providers for critical services', 'assigned_to' => 1, 'due_date' => '2026-09-20', 'status' => 'pending'],
            ['risk_assessment_id' => 8, 'action' => 'Automated Backup Testing', 'description' => 'Implement automated backup verification and reporting', 'assigned_to' => 1, 'due_date' => '2026-08-25', 'status' => 'in_progress'],
        ];

        foreach ($plans as $plan) {
            RiskTreatmentPlan::create($plan);
        }
    }
}