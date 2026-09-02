<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assessment_responses', function (Blueprint $table) {
            $table->json('evidence_files')->nullable()->after('evidence_notes');
            $table->text('not_applicable_reason')->nullable()->after('gap_description');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_responses', function (Blueprint $table) {
            $table->dropColumn(['evidence_files', 'not_applicable_reason']);
        });
    }
};