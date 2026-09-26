<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PolicyTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            'Information Security Policy',
            'Access Control Policy',
            'Incident Response Procedure',
            'Risk Assessment Methodology',
            'Business Continuity Plan',
            'Data Classification Policy',
            'Acceptable Use Policy',
            'Supplier Security Policy',
            'Change Management Procedure',
            'Backup & Recovery Policy',
        ];

        foreach ($templates as $template) {
            // Simplified: just create placeholder files
            $path = "templates/" . str_replace(' ', '_', $template) . '.txt';
            Storage::disk('local')->put($path, "Template: {$template}\n\nPlaceholder content.");
        }
    }
}