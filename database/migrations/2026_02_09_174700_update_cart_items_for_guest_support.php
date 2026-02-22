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
        Schema::table('cart_items', function (Blueprint $table) {
            // 1. Make user_id nullable so guests don't need an account
            $table->foreignId('user_id')->nullable()->change();

            // 2. Add session_id to identify the guest's browser
            // We add an index because we will be searching by this column often
            $table->string('session_id')->nullable()->after('user_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Remove the session_id column
            $table->dropColumn('session_id');

            // Make user_id NOT nullable again (Note: This might fail if you have null data)
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
