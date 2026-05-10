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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            // Parent sale transaction
            $table->foreignId('sale_id')
                ->constrained()
                ->cascadeOnDelete();

            // Sold product
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Quantity sold
            $table->integer('quantity');

            // Product price during sale
            $table->decimal('price', 15, 2);

            // quantity * price
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};