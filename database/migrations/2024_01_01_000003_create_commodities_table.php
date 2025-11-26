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
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 50)->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('location_id')->constrained()->restrictOnDelete();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('acquisition_type', ['pembelian', 'hibah', 'bantuan', 'produksi', 'lainnya'])->default('pembelian');
            $table->string('acquisition_source')->nullable();
            $table->integer('quantity')->default(1);
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->year('purchase_year')->nullable();
            $table->decimal('purchase_price', 15, 2)->default(0);
            $table->text('specifications')->nullable();
            $table->text('notes')->nullable();
            $table->string('responsible_person')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('item_code');
            $table->index('category_id');
            $table->index('location_id');
            $table->index('condition');
            $table->index('created_by');
            $table->index('deleted_at');
            $table->fullText(['name', 'brand', 'notes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
