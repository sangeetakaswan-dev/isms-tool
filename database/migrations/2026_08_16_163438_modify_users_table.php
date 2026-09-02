// database/migrations/2024_01_01_000002_modify_users_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('email');
            $table->string('phone')->nullable()->after('avatar_path');
            $table->string('job_title')->nullable()->after('phone');
            $table->string('department')->nullable()->after('job_title');
            $table->boolean('is_active')->default(true)->after('department');
            $table->foreignId('current_tenant_id')->nullable()->after('is_active');
            $table->timestamp('last_login_at')->nullable()->after('current_tenant_id');

            $table->foreign('current_tenant_id')
                ->references('id')
                ->on('tenants')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_tenant_id']);
            $table->dropColumn([
                'avatar_path',
                'phone',
                'job_title',
                'department',
                'is_active',
                'current_tenant_id',
                'last_login_at'
            ]);
        });
    }
};