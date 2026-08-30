<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\RiskAssessment;
use App\Models\RiskTreatmentPlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class RiskManagementSeeder extends Seeder
{
    public function run()
    {
        // 1. Get or create tenant
        $tenant = Tenant::first();
        if (!$tenant) {
            $tenant = Tenant::create([
                'name' => 'Demo Organization',
                'slug' => 'demo-org',
                'contact_email' => 'admin@demo.com',
                'country' => 'India',
            ]);
        }
        $tenantId = $tenant->id;

        // 2. Get a user (any) for risk_owner
        $user = User::first();

        // 3. Create assets and store them in an array with their IDs
        $assetData = [
            ['name' => 'CRM Database', 'asset_type' => 'data', 'confidentiality_rating' => 5, 'integrity_rating' => 4, 'availability_rating' => 4],
            ['name' => 'Office Network', 'asset_type' => 'facility', 'confidentiality_rating' => 3, 'integrity_rating' => 3, 'availability_rating' => 5],
            ['name' => 'Employee Laptops', 'asset_type' => 'hardware', 'confidentiality_rating' => 4, 'integrity_rating' => 3, 'availability_rating' => 3],
            ['name' => 'Cloud Infrastructure', 'asset_type' => 'service', 'confidentiality_rating' => 4, 'integrity_rating' => 4, 'availability_rating' => 5],
            ['name' => 'HR Records', 'asset_type' => 'data', 'confidentiality_rating' => 5, 'integrity_rating' => 5, 'availability_rating' => 2],
        ];

        $assets = [];
        foreach ($assetData as $data) {
            $asset = Asset::create(array_merge($data, ['tenant_id' => $tenantId]));
            $assets[] = $asset;
        }

        // Now $assets[0] is first asset, $assets[1] second, etc.

        // 4. Create risks using the actual asset IDs
        $riskDefinitions = [
            ['asset_index' => 0, 'threat' => 'Data Breach', 'vulnerability' => 'Weak Access Controls', 'likelihood' => 4, 'impact' => 5],
            ['asset_index' => 0, 'threat' => 'SQL Injection', 'vulnerability' => 'Unpatched Software', 'likelihood' => 3, 'impact' => 4],
            ['asset_index' => 1, 'threat' => 'Network Outage', 'vulnerability' => 'Single Point of Failure', 'likelihood' => 3, 'impact' => 5],
            ['asset_index' => 2, 'threat' => 'Theft', 'vulnerability' => 'No Encryption', 'likelihood' => 2, 'impact' => 4],
            ['asset_index' => 3, 'threat' => 'Unauthorized Access', 'vulnerability' => 'Weak IAM Policies', 'likelihood' => 4, 'impact' => 4],
            ['asset_index' => 4, 'threat' => 'Data Leakage', 'vulnerability' => 'Insufficient Data Masking', 'likelihood' => 3, 'impact' => 5],
        ];

        foreach ($riskDefinitions as $def) {
            $asset = $assets[$def['asset_index']];
            $riskScore = $def['likelihood'] * $def['impact'];
            $riskLevel = $this->getRiskLevel($riskScore);

            $risk = RiskAssessment::create([
                'tenant_id' => $tenantId,
                'asset_id' => $asset->id,
                'threat' => $def['threat'],
                'vulnerability' => $def['vulnerability'],
                'likelihood' => $def['likelihood'],
                'impact' => $def['impact'],
                'risk_score' => $riskScore,
                'risk_level' => $riskLevel,
                'status' => 'identified',
                'risk_owner' => $user ? $user->id : null,
            ]);

            // 5. Create treatment plans for some risks (e.g., risk IDs 1,3,5 - but we use dynamic)
            // We'll create for every 2nd risk (just for demo)
            static $counter = 0;
            $counter++;
            if (in_array($counter, [1, 3, 5])) {
                RiskTreatmentPlan::create([
                    'risk_assessment_id' => $risk->id,
                    'action' => 'Implement ' . ($def['threat'] == 'Data Breach' ? 'MFA' : 'Backup Solution'),
                    'description' => 'Detailed action plan for ' . $def['threat'],
                    'assigned_to' => $user ? $user->id : null,
                    'due_date' => now()->addMonths(2),
                    'status' => 'pending',
                    'cost_estimate' => 5000,
                ]);
            }
        }
    }

    private function getRiskLevel($score)
    {
        if ($score >= 17)
            return 'critical';
        if ($score >= 10)
            return 'high';
        if ($score >= 5)
            return 'medium';
        return 'low';
    }
}