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
        Schema::create('report_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('signable_type'); // disposal, maintenance, transfer
            $table->unsignedBigInteger('signable_id');
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('signature_hash')->unique();
            $table->string('content_hash');
            $table->timestamp('signed_at');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_valid')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index(['signable_type', 'signable_id']);
            $table->index('signature_hash');
            $table->index('user_id');
            $table->index('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_signatures');
    }
};
