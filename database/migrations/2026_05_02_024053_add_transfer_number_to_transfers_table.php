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
        Schema::table('transfers', function (Blueprint $table) {
            // Add readable transfer number (e.g. TRF-0001)
            $table->string('transfer_number')->nullable()->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            // Remove column if rolled back
            $table->dropColumn('transfer_number');
        });
    }
};
