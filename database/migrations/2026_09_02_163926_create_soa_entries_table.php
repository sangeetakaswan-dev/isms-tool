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
        Schema::create('soa_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('control_id')->constrained()->cascadeOnDelete();
            $table->boolean('applicable')->default(true);
            $table->text('justification')->nullable();
            $table->enum('implementation_status', ['fully_implemented', 'partially_implemented', 'not_implemented', 'not_applicable'])
                ->default('not_implemented');
            $table->text('implementation_description')->nullable();
            $table->text('exclusion_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->unique(['assessment_id', 'control_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('soa_entries');
    }
};