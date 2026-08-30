<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('threat');
            $table->string('vulnerability');
            $table->integer('likelihood')->default(3);
            $table->integer('impact')->default(3);
            $table->integer('risk_score')->nullable();
            $table->string('risk_level')->nullable();
            $table->string('treatment')->nullable(); // accept, mitigate, transfer, avoid
            $table->text('treatment_description')->nullable();
            $table->integer('residual_likelihood')->nullable();
            $table->integer('residual_impact')->nullable();
            $table->integer('residual_score')->nullable();
            $table->string('residual_level')->nullable();
            $table->foreignId('risk_owner')->nullable()->constrained('users');
            $table->string('status')->default('identified');
            $table->date('review_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
