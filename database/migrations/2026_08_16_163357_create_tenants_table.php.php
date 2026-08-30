// database/migrations/2024_01_01_000001_create_tenants_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->string('industry')->nullable();
            $table->enum('size', ['1-10', '11-50', '51-200', '201-1000', '1000+']);
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('pincode')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('slug');
            $table->index('industry');
            $table->index('country');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};