<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run()
    {
        $assets = [
            ['name' => 'CRM Database', 'asset_type' => 'data', 'confidentiality_rating' => 5, 'integrity_rating' => 4, 'availability_rating' => 3],
            ['name' => 'Office Network', 'asset_type' => 'hardware', 'confidentiality_rating' => 3, 'integrity_rating' => 4, 'availability_rating' => 5],
            ['name' => 'Employee Laptops', 'asset_type' => 'hardware', 'confidentiality_rating' => 4, 'integrity_rating' => 3, 'availability_rating' => 4],
            ['name' => 'Cloud Infrastructure', 'asset_type' => 'service', 'confidentiality_rating' => 5, 'integrity_rating' => 5, 'availability_rating' => 5],
            ['name' => 'HR Records', 'asset_type' => 'data', 'confidentiality_rating' => 5, 'integrity_rating' => 4, 'availability_rating' => 2],
            ['name' => 'Source Code Repository', 'asset_type' => 'software', 'confidentiality_rating' => 4, 'integrity_rating' => 5, 'availability_rating' => 3],
            ['name' => 'Office Premises', 'asset_type' => 'facility', 'confidentiality_rating' => 2, 'integrity_rating' => 2, 'availability_rating' => 4],
            ['name' => 'Backup Servers', 'asset_type' => 'hardware', 'confidentiality_rating' => 4, 'integrity_rating' => 5, 'availability_rating' => 5],
            ['name' => 'Email System', 'asset_type' => 'software', 'confidentiality_rating' => 4, 'integrity_rating' => 3, 'availability_rating' => 4],
            ['name' => 'Financial Data', 'asset_type' => 'data', 'confidentiality_rating' => 5, 'integrity_rating' => 5, 'availability_rating' => 4],
        ];

        foreach ($assets as $asset) {
            Asset::create(array_merge($asset, ['tenant_id' => 1]));
        }
    }
}