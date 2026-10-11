<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Make existing columns nullable + add new columns
        Schema::table('risk_treatment_plans', function (Blueprint $table) {
            // Make risk_assessment_id nullable (Phase 3 me NOT NULL tha)
            if (Schema::hasColumn('risk_treatment_plans', 'risk_assessment_id')) {
                $table->unsignedBigInteger('risk_assessment_id')->nullable()->change();
            }

            // Add risk_id FK (nullable) — Phase 7 new
            if (!Schema::hasColumn('risk_treatment_plans', 'risk_id')) {
                $table->foreignId('risk_id')->nullable()
                      ->after('id')
                      ->constrained('risks')->nullOnDelete();
            }

            // Add strategy
            if (!Schema::hasColumn('risk_treatment_plans', 'strategy')) {
                $table->enum('strategy', ['mitigate', 'accept', 'transfer', 'avoid'])
                      ->default('mitigate')->after('risk_id');
            }

            // Add completion_notes
            if (!Schema::hasColumn('risk_treatment_plans', 'completion_notes')) {
                $table->text('completion_notes')->nullable()->after('status');
            }

            // Add completed_at
            if (!Schema::hasColumn('risk_treatment_plans', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('completion_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('risk_treatment_plans', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['risk_id', 'strategy', 'completion_notes', 'completed_at'] as $col) {
                if (Schema::hasColumn('risk_treatment_plans', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};