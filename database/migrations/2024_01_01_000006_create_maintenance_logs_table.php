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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->constrained()->cascadeOnDelete();
            $table->date('maintenance_date');
            $table->string('maintenance_type')->nullable();
            $table->text('description');
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('performed_by')->nullable();
            $table->string('vendor')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->enum('condition_after', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index('commodity_id');
            $table->index('maintenance_date');
            $table->index('next_maintenance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
