<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $dbDriver = \Illuminate\Support\Facades\DB::getDriverName();
        if ($dbDriver === 'sqlite') {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('connection_code', 'users_connection_code_unique');
                });
            } catch (\Exception $e) {}
        } else {
            $indexes = collect(\Illuminate\Support\Facades\DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_connection_code_unique'"));
            if ($indexes->isEmpty()) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('connection_code', 'users_connection_code_unique');
                });
            }
        }

        // Gap 2: Track when an employee code was last rotated so we can
        // show managers when a code was generated / force periodic rotation.
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('connection_code_issued_at')->nullable()->after('connection_code');
        });

        // Gap 5: Add extension_device_id index to extension_daily_rollups
        // so team-dashboard queries can filter by device efficiently.
        Schema::table('extension_daily_rollups', function (Blueprint $table) {
            if (!Schema::hasColumn('extension_daily_rollups', 'extension_device_id')) {
                $table->uuid('extension_device_id')->nullable()->after('user_id');
                $table->index('extension_device_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_connection_code_unique');
            $table->dropColumn('connection_code_issued_at');
        });

        Schema::table('extension_daily_rollups', function (Blueprint $table) {
            $table->dropIndex(['extension_device_id']);
            $table->dropColumn('extension_device_id');
        });
    }
};
