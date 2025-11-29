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
        Schema::table('disposals', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('rejection_reason');
            $table->decimal('disposal_value', 15, 2)->nullable()->after('estimated_value');
            $table->string('disposal_method')->nullable()->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposals', function (Blueprint $table) {
            $table->dropColumn(['attachments', 'disposal_value', 'disposal_method']);
        });
    }
};
