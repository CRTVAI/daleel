<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('extension_devices', function (Blueprint $table) {
            $table->unsignedBigInteger('token_id')->nullable()->after('revoked_at');
            $table->foreign('token_id')->references('id')->on('personal_access_tokens')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('extension_devices', function (Blueprint $table) {
            $table->dropForeign(['token_id']);
            $table->dropColumn('token_id');
        });
    }
};
