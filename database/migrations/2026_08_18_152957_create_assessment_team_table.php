<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('assessor'); // lead_assessor, assessor, reviewer
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['assessment_id', 'user_id']);
            $table->index(['assessment_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_team');
    }
};