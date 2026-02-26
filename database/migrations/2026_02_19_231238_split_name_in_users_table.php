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
        Schema::table('users', function (Blueprint $table) {
            // add new columns
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            // unique username for login
            $table->string('username')->unique()->after('last_name');
            // remove old column
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // restore name if rollback
            $table->string('name')->after('id');

            $table->dropColumn(['first_name', 'last_name','username']);
        });
    }
};
