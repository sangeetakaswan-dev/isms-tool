// database/migrations/2024_01_01_000008_create_assessment_responses_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assessment_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('control_id')->constrained()->cascadeOnDelete();
            $table->enum('status', [
                'compliant',
                'non_compliant',
                'partially_compliant',
                'not_applicable',
                'not_assessed'
            ])->default('not_assessed');
            $table->integer('maturity_level')->nullable(); // 0-5
            $table->text('evidence_notes')->nullable();
            $table->text('gap_description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->date('due_date')->nullable();
            $table->foreignId('assessed_by')->nullable()->constrained('users');
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();

            $table->unique(['assessment_id', 'control_id']);
            $table->index('status');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_responses');
    }
};