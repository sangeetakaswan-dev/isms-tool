// database/migrations/2024_01_01_000005_create_domains_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., 'A.5', 'A.6', '4.1'
            $table->string('name');
            $table->text('description');
            $table->enum('clause_type', ['main_clause', 'annex_a']);
            $table->integer('sort_order');
            $table->timestamps();

            $table->index(['clause_type', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};