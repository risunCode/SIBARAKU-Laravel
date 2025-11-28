<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('referral_codes', function (Blueprint $table) {
            $table->string('role')->nullable()->after('expires_at')->comment('Role yang akan diberikan ke user (admin, staff, etc.)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_codes', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
