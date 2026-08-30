<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [
            // Main Clauses (Organizational)
            [
                'code' => '4',
                'name' => 'Context of the Organization',
                'description' => 'Understanding the organization and its context, needs and expectations of interested parties, determining the scope of the ISMS, and the information security management system.',
                'clause_type' => 'main_clause',
                'sort_order' => 1,
            ],
            [
                'code' => '5',
                'name' => 'Leadership',
                'description' => 'Leadership and commitment, information security policy, and organizational roles, responsibilities and authorities.',
                'clause_type' => 'main_clause',
                'sort_order' => 2,
            ],
            [
                'code' => '6',
                'name' => 'Planning',
                'description' => 'Actions to address risks and opportunities, information security objectives and planning to achieve them.',
                'clause_type' => 'main_clause',
                'sort_order' => 3,
            ],
            [
                'code' => '7',
                'name' => 'Support',
                'description' => 'Resources, competence, awareness, communication, and documented information.',
                'clause_type' => 'main_clause',
                'sort_order' => 4,
            ],
            [
                'code' => '8',
                'name' => 'Operation',
                'description' => 'Operational planning and control, information security risk assessment, and information security risk treatment.',
                'clause_type' => 'main_clause',
                'sort_order' => 5,
            ],
            [
                'code' => '9',
                'name' => 'Performance Evaluation',
                'description' => 'Monitoring, measurement, analysis and evaluation, internal audit, and management review.',
                'clause_type' => 'main_clause',
                'sort_order' => 6,
            ],
            [
                'code' => '10',
                'name' => 'Improvement',
                'description' => 'Continual improvement, nonconformity and corrective action.',
                'clause_type' => 'main_clause',
                'sort_order' => 7,
            ],
            // Annex A - Organizational Controls
            [
                'code' => 'A.5',
                'name' => 'Organizational Controls',
                'description' => 'Information security policies, organization of information security, and asset management.',
                'clause_type' => 'annex_a',
                'sort_order' => 8,
            ],
            // Annex A - People Controls
            [
                'code' => 'A.6',
                'name' => 'People Controls',
                'description' => 'Human resource security, awareness, education and training, and disciplinary process.',
                'clause_type' => 'annex_a',
                'sort_order' => 9,
            ],
            // Annex A - Physical Controls
            [
                'code' => 'A.7',
                'name' => 'Physical Controls',
                'description' => 'Physical security perimeter, physical entry, securing offices, rooms and facilities, and protection from threats.',
                'clause_type' => 'annex_a',
                'sort_order' => 10,
            ],
            // Annex A - Technological Controls
            [
                'code' => 'A.8',
                'name' => 'Technological Controls',
                'description' => 'Access control, cryptography, operations security, communications security, and system acquisition, development and maintenance.',
                'clause_type' => 'annex_a',
                'sort_order' => 11,
            ],
        ];

        foreach ($domains as $domain) {
            Domain::create($domain);
        }
    }
}