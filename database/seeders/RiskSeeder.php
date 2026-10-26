<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class RiskSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-organization')->first();
        $owner = User::where('email', 'owner@demo.com')->first();

        if (!$tenant) {
            $this->command->warn('⚠ No demo tenant found. Run UserSeeder first.');
            return;
        }

        $assets = Asset::where('tenant_id', $tenant->id)->get()->keyBy('name');

        $risks = [
            [
                'title' => 'Unauthorized access to CRM database',
                'description' => 'Weak access controls could allow unauthorized users to access customer PII.',
                'category' => 'technical',
                'likelihood' => 4,
                'impact' => 5,
                'asset_name' => 'CRM Database Server',
                'status' => 'assessed',
            ],
            [
                'title' => 'Phishing attack on employees',
                'description' => 'Employees may fall victim to phishing emails leading to credential theft.',
                'category' => 'people',
                'likelihood' => 5,
                'impact' => 4,
                'asset_name' => null,
                'status' => 'treated',
            ],
            [
                'title' => 'Cloud misconfiguration exposing data',
                'description' => 'S3 bucket or IAM misconfiguration could expose sensitive data publicly.',
                'category' => 'technical',
                'likelihood' => 3,
                'impact' => 5,
                'asset_name' => 'Cloud Infrastructure (AWS)',
                'status' => 'identified',
            ],
            [
                'title' => 'Laptop theft with unencrypted data',
                'description' => 'Employee laptops stolen while traveling could expose company data if unencrypted.',
                'category' => 'physical',
                'likelihood' => 3,
                'impact' => 4,
                'asset_name' => 'Employee Laptops',
                'status' => 'assessed',
            ],
            [
                'title' => 'Ransomware attack on file servers',
                'description' => 'Ransomware could encrypt critical files and demand payment.',
                'category' => 'technical',
                'likelihood' => 3,
                'impact' => 5,
                'asset_name' => 'Email Server',
                'status' => 'monitored',
            ],
            [
                'title' => 'Insider threat from departing employees',
                'description' => 'Departing employees could exfiltrate data before leaving.',
                'category' => 'organizational',
                'likelihood' => 3,
                'impact' => 4,
                'asset_name' => 'Source Code Repository',
                'status' => 'treated',
            ],
            [
                'title' => 'DDoS attack on public services',
                'description' => 'Distributed denial of service could make services unavailable.',
                'category' => 'technical',
                'likelihood' => 3,
                'impact' => 3,
                'asset_name' => 'Cloud Infrastructure (AWS)',
                'status' => 'identified',
            ],
            [
                'title' => 'Physical break-in to data center',
                'description' => 'Unauthorized physical access to servers could lead to data theft.',
                'category' => 'physical',
                'likelihood' => 2,
                'impact' => 5,
                'asset_name' => 'HQ Office Building',
                'status' => 'assessed',
            ],
            [
                'title' => 'Third-party vendor data breach',
                'description' => 'Vendor handling customer data could suffer a breach.',
                'category' => 'compliance',
                'likelihood' => 3,
                'impact' => 4,
                'asset_name' => 'HR Management System',
                'status' => 'identified',
            ],
            [
                'title' => 'Weak password policies',
                'description' => 'Users may set weak passwords that can be brute-forced.',
                'category' => 'organizational',
                'likelihood' => 4,
                'impact' => 3,
                'asset_name' => null,
                'status' => 'treated',
            ],
        ];

        foreach ($risks as $data) {
            $likelihood = $data['likelihood'];
            $impact = $data['impact'];
            $score = $likelihood * $impact;

            Risk::firstOrCreate(
                ['tenant_id' => $tenant->id, 'title' => $data['title']],
                [
                    'tenant_id' => $tenant->id,
                    'asset_id' => $data['asset_name'] ? ($assets[$data['asset_name']]->id ?? null) : null,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'likelihood' => $likelihood,
                    'impact' => $impact,
                    'risk_score' => $score,
                    'risk_level' => $this->levelFor($score),
                    'status' => $data['status'],
                    'owner_id' => $owner?->id,
                    'identified_at' => now()->subDays(rand(10, 90)),
                ]
            );
        }

        $this->command->info('✓ ' . count($risks) . ' demo risks created');
    }

    private function levelFor(int $score): string
    {
        return match (true) {
            $score >= 20 => 'critical',
            $score >= 12 => 'high',
            $score >= 6 => 'medium',
            default => 'low',
        };
    }
}