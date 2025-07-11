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
        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('system_stock'); // Stock according to system
            $table->integer('physical_stock')->nullable(); // Actual counted stock
            $table->integer('variance')->nullable(); // Difference (physical - system)
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('variance_value', 15, 2)->default(0); // variance * unit_cost
            $table->text('notes')->nullable();
            $table->boolean('is_counted')->default(false);
            $table->foreignId('counted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('counted_at')->nullable();
            $table->timestamps();

            $table->unique(['stock_opname_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname_details');
    }
};
