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

            // Sale belongs to a warehouse (store location)
            $table->foreignId('warehouse_id')
                ->constrained()
                ->cascadeOnDelete();

            // Who processed the sale
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Total computed after items
            $table->decimal('total_amount', 15, 2)->default(0);

            $table->string('status')->default('completed');

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
