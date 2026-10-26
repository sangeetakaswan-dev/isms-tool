<?php

namespace Database\Seeders;

use App\Models\Control;
use App\Models\Document;
use App\Models\DocumentReview;
use App\Models\DocumentVersion;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        $owner = User::where('email', 'owner@demo.com')->first();
        $assessor = User::where('email', 'assessor@demo.com')->first();

        if (!$tenant || !$owner)
            return;

        $docs = [
            ['title' => 'Information Security Policy', 'type' => 'policy', 'status' => 'approved'],
            ['title' => 'Access Control Policy', 'type' => 'policy', 'status' => 'approved'],
            ['title' => 'Risk Management Procedure', 'type' => 'procedure', 'status' => 'under_review'],
            ['title' => 'Supplier Security Policy', 'type' => 'policy', 'status' => 'draft'],
            ['title' => 'Incident Response Plan', 'type' => 'procedure', 'status' => 'approved'],
        ];

        $controls = Control::take(15)->get();

        foreach ($docs as $d) {
            $document = Document::create([
                'tenant_id' => $tenant->id,
                'title' => $d['title'],
                'document_type' => $d['type'],
                'category' => 'Information Security',
                'current_version' => '1.0',
                'file_path' => 'documents/demo.pdf',
                'status' => $d['status'],
                'owner_id' => $owner->id,
                'approved_by' => $d['status'] === 'approved' ? $owner->id : null,
                'approved_at' => $d['status'] === 'approved' ? now() : null,
                'review_date' => now()->addMonths(6),
                'description' => 'Standard ' . $d['title'] . ' for the ISMS.',
            ]);

            // Version
            DocumentVersion::create([
                'document_id' => $document->id,
                'version' => '1.0',
                'file_path' => 'documents/demo.pdf',
                'changes' => 'Initial version',
                'created_by' => $owner->id,
            ]);

            // Review
            if ($d['status'] === 'under_review') {
                DocumentReview::create([
                    'document_id' => $document->id,
                    'reviewer_id' => $assessor->id,
                    'status' => 'pending',
                    'comments' => 'Please review sections 4.2 and 5.1.',
                ]);
            }

            // Link to 3 random controls (Direct DB insert for pivot table)
            foreach ($controls->random(3) as $control) {
                DB::table('document_control_map')->insert([
                    'document_id' => $document->id,
                    'control_id' => $control->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✓ Documents, Versions, Reviews & Mappings created');
    }
}