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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment', 'opname']);
            $table->integer('quantity');
            $table->integer('previous_stock');
            $table->integer('current_stock');
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->string('reference_type')->nullable(); // 'purchase', 'sale', 'adjustment', 'opname'
            $table->unsignedBigInteger('reference_id')->nullable(); // ID of related transaction
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who made the movement
            $table->timestamp('movement_date');
            $table->timestamps();

            $table->index(['product_id', 'movement_date']);
            $table->index(['type', 'movement_date']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
