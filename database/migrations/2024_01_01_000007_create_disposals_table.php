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
        Schema::create('disposals', function (Blueprint $table) {
            $table->id();
            $table->string('disposal_number', 50)->unique();
            $table->foreignId('commodity_id')->constrained()->restrictOnDelete();
            $table->date('disposal_date')->nullable();
            $table->enum('reason', ['rusak_berat', 'hilang', 'usang', 'dicuri', 'dijual', 'dihibahkan', 'lainnya'])->default('rusak_berat');
            $table->text('description')->nullable();
            $table->decimal('estimated_value', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('requested_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index('commodity_id');
            $table->index('status');
            $table->index('requested_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposals');
    }
};
