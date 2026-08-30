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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('asset_type'); // hardware, software, data, people, facility, service
            $table->string('owner')->nullable();
            $table->string('department')->nullable();
            $table->string('location')->nullable();
            $table->integer('confidentiality_rating')->default(3);
            $table->integer('integrity_rating')->default(3);
            $table->integer('availability_rating')->default(3);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
