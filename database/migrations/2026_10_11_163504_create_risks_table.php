<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('risks', function (Blueprint $table) {
           $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('category', 50)->default('organizational');
            $table->tinyInteger('likelihood')->unsigned()->default(1);
            $table->tinyInteger('impact')->unsigned()->default(1);
            $table->smallInteger('risk_score')->unsigned()->default(1);
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->enum('status', ['identified', 'assessed', 'treated', 'monitored', 'closed'])
                  ->default('identified');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('identified_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'risk_level']);
            $table->index(['tenant_id', 'owner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
