<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('report_type', 50);
            $table->json('metrics');
            $table->date('snapshot_date');
            $table->timestamps();

            $table->unique(['tenant_id', 'report_type', 'snapshot_date'], 'snapshot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_snapshots');
    }
};