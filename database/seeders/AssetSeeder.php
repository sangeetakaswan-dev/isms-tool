<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'demo-organization')->first();
        if (!$tenant) {
            $this->command->warn('⚠ No demo tenant found. Run UserSeeder first.');
            return;
        }

        $assets = [
            [
                'name' => 'CRM Database Server',
                'asset_type' => 'hardware',
                'owner' => 'IT Infrastructure Team',
                'department' => 'IT',
                'location' => 'Data Center A',
                'confidentiality_rating' => 5,
                'integrity_rating' => 5,
                'availability_rating' => 5,
                'description' => 'Primary CRM database containing customer PII and business data.',
            ],
            [
                'name' => 'Office Network Switch',
                'asset_type' => 'hardware',
                'owner' => 'Network Team',
                'department' => 'IT',
                'location' => 'HQ Server Room',
                'confidentiality_rating' => 3,
                'integrity_rating' => 4,
                'availability_rating' => 5,
                'description' => 'Core network switch connecting office infrastructure.',
            ],
            [
                'name' => 'Employee Laptops',
                'asset_type' => 'hardware',
                'owner' => 'IT Support',
                'department' => 'All',
                'location' => 'Various',
                'confidentiality_rating' => 3,
                'integrity_rating' => 3,
                'availability_rating' => 3,
                'description' => 'Fleet of laptops issued to all employees.',
            ],
            [
                'name' => 'Cloud Infrastructure (AWS)',
                'asset_type' => 'service',
                'owner' => 'Cloud Team',
                'department' => 'IT',
                'location' => 'Cloud (AWS ap-south-1)',
                'confidentiality_rating' => 4,
                'integrity_rating' => 5,
                'availability_rating' => 5,
                'description' => 'AWS infrastructure hosting production workloads.',
            ],
            [
                'name' => 'Customer Database',
                'asset_type' => 'data',
                'owner' => 'Data Team',
                'department' => 'Operations',
                'location' => 'CRM Database Server',
                'confidentiality_rating' => 5,
                'integrity_rating' => 5,
                'availability_rating' => 4,
                'description' => 'Customer records, transactions, and PII.',
            ],
            [
                'name' => 'HR Management System',
                'asset_type' => 'software',
                'owner' => 'HR Team',
                'department' => 'HR',
                'location' => 'SaaS (Cloud)',
                'confidentiality_rating' => 4,
                'integrity_rating' => 4,
                'availability_rating' => 4,
                'description' => 'Employee records, payroll, and HR workflows.',
            ],
            [
                'name' => 'Email Server',
                'asset_type' => 'software',
                'owner' => 'IT Team',
                'department' => 'IT',
                'location' => 'Data Center A',
                'confidentiality_rating' => 4,
                'integrity_rating' => 4,
                'availability_rating' => 5,
                'description' => 'Corporate email server (Exchange).',
            ],
            [
                'name' => 'HQ Office Building',
                'asset_type' => 'facility',
                'owner' => 'Facilities Team',
                'department' => 'Operations',
                'location' => 'Mumbai, India',
                'confidentiality_rating' => 2,
                'integrity_rating' => 3,
                'availability_rating' => 5,
                'description' => 'Headquarters office premises.',
            ],
            [
                'name' => 'Source Code Repository',
                'asset_type' => 'data',
                'owner' => 'Engineering Team',
                'department' => 'Engineering',
                'location' => 'GitHub (Cloud)',
                'confidentiality_rating' => 5,
                'integrity_rating' => 5,
                'availability_rating' => 4,
                'description' => 'Proprietary source code and IP.',
            ],
            [
                'name' => 'Firewall (Perimeter)',
                'asset_type' => 'hardware',
                'owner' => 'Security Team',
                'department' => 'IT',
                'location' => 'Data Center A',
                'confidentiality_rating' => 3,
                'integrity_rating' => 5,
                'availability_rating' => 5,
                'description' => 'Perimeter firewall (Palo Alto) for network security.',
            ],
        ];

        foreach ($assets as $data) {
            Asset::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $data['name']],
                array_merge($data, ['tenant_id' => $tenant->id])
            );
        }

        $this->command->info('✓ ' . count($assets) . ' demo assets created');
    }
}