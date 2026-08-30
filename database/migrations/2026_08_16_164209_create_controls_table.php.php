// database/migrations/2024_01_01_000006_create_controls_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('control_id')->unique(); // e.g., '5.1', 'A.5.1'
            $table->string('title');
            $table->text('description');
            $table->text('implementation_guidance')->nullable();
            $table->enum('category', ['organizational', 'people', 'physical', 'technological']);
            $table->string('control_type')->nullable(); // preventive, detective, corrective
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('control_id');
            $table->index('category');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controls');
    }
};