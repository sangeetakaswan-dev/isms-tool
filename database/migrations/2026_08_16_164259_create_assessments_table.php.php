// database/migrations/2024_01_01_000007_create_assessments_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('scope')->nullable();
            $table->date('start_date');
            $table->date('target_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed', 'archived']);
            $table->integer('progress_percentage')->default(0);
            $table->foreignId('lead_assessor_id')->constrained('users');
            $table->json('team_members')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};