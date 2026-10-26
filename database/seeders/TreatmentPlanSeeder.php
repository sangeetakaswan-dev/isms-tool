<?php

namespace Database\Seeders;

use App\Models\Risk;
use App\Models\RiskTreatmentPlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class TreatmentPlanSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-organization')->first();
        $assessor = User::where('email', 'assessor@demo.com')->first();

        if (!$tenant) {
            $this->command->warn('⚠ No demo tenant found. Run UserSeeder first.');
            return;
        }

        $riskMap = Risk::where('tenant_id', $tenant->id)->pluck('id', 'title');

        $plans = [
            [
                'risk_title' => 'Phishing attack on employees',
                'strategy' => 'mitigate',
                'action' => 'Deploy security awareness training program and phishing simulation quarterly.',
                'cost_estimate' => 50000,
                'due_date' => now()->addDays(30)->toDateString(),
                'status' => 'in_progress',
            ],
            [
                'risk_title' => 'Phishing attack on employees',
                'strategy' => 'mitigate',
                'action' => 'Enable MFA for all employee email accounts.',
                'cost_estimate' => 25000,
                'due_date' => now()->addDays(15)->toDateString(),
                'status' => 'completed',
            ],
            [
                'risk_title' => 'Unauthorized access to CRM database',
                'strategy' => 'mitigate',
                'action' => 'Implement database access control lists and audit logging.',
                'cost_estimate' => 75000,
                'due_date' => now()->addDays(45)->toDateString(),
                'status' => 'pending',
            ],
            [
                'risk_title' => 'Cloud misconfiguration exposing data',
                'strategy' => 'mitigate',
                'action' => 'Deploy CSPM tools and mandatory IaC reviews.',
                'cost_estimate' => 100000,
                'due_date' => now()->addDays(60)->toDateString(),
                'status' => 'pending',
            ],
            [
                'risk_title' => 'Insider threat from departing employees',
                'strategy' => 'mitigate',
                'action' => 'Enforce offboarding checklist and access revocation within 24h.',
                'cost_estimate' => 15000,
                'due_date' => now()->addDays(20)->toDateString(),
                'status' => 'completed',
            ],
            [
                'risk_title' => 'Weak password policies',
                'strategy' => 'mitigate',
                'action' => 'Enable password complexity requirements and 90-day rotation.',
                'cost_estimate' => 5000,
                'due_date' => now()->addDays(10)->toDateString(),
                'status' => 'completed',
            ],
            [
                'risk_title' => 'Ransomware attack on file servers',
                'strategy' => 'mitigate',
                'action' => 'Implement 3-2-1 backup strategy with off-site copies.',
                'cost_estimate' => 80000,
                'due_date' => now()->addDays(25)->toDateString(),
                'status' => 'in_progress',
            ],
        ];

        foreach ($plans as $data) {
            $riskId = $riskMap[$data['risk_title']] ?? null;
            if (!$riskId)
                continue;

            RiskTreatmentPlan::firstOrCreate(
                [
                    'risk_id' => $riskId,
                    'action' => $data['action'],
                ],
                [
                    'risk_id' => $riskId,
                    'strategy' => $data['strategy'],
                    'action' => $data['action'],
                    'cost_estimate' => $data['cost_estimate'],
                    'due_date' => $data['due_date'],
                    'assigned_to' => $assessor?->id,
                    'status' => $data['status'],
                    'completed_at' => $data['status'] === 'completed' ? now() : null,
                ]
            );
        }

        $this->command->info('✓ ' . count($plans) . ' demo treatment plans created');
    }
}