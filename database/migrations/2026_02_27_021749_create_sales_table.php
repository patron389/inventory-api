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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Receipt / invoice number
            $table->string('invoice_no')->unique();

            // Warehouse / branch
            $table->foreignId('warehouse_id')
                ->constrained()
                ->cascadeOnDelete();

            // Cashier / user
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Amount before discounts/tax
            $table->decimal('subtotal', 15, 2)->default(0);

            // Discount amount
            $table->decimal('discount', 15, 2)->default(0);

            // Tax amount
            $table->decimal('tax', 15, 2)->default(0);

            // Final total
            $table->decimal('total_amount', 15, 2)->default(0);

            // Cash/payment received
            $table->decimal('payment_amount', 15, 2)->default(0);

            // Change returned
            $table->decimal('change_amount', 15, 2)->default(0);

            // Sale status
            $table->enum('status', [
                'completed',
                'cancelled',
                'refunded'
            ])->default('completed');

            // Optional remarks
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};