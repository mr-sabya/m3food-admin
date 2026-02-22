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
        Schema::table('product_variants', function (Blueprint $table) {
            // This adds the 'deleted_at' column
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // This removes the column if you rollback
            $table->dropSoftDeletes();
        });
    }
};
