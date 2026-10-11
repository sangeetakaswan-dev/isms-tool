<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('report_type', 50);
            $table->string('format', 10);
            $table->string('file_path')->nullable();
            $table->json('parameters')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'report_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_reports');
    }
};