<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove foreign key constraint first
        DB::statement('ALTER TABLE transfers DROP FOREIGN KEY transfers_product_id_foreign');

        // Then drop the columns
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['product_id', 'quantity']);
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
        });
    }
};