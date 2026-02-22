<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            // 1. Drop the Foreign Key first (Crucial step)
            $table->dropForeign(['user_id']); 

            // 2. Now drop the unique index
            $table->dropUnique(['user_id']);

            // 3. Remove the metadata columns
            $table->dropColumn(['name', 'is_public']);

            // 4. Add the product_id column
            $table->foreignId('product_id')->after('user_id')->constrained()->onDelete('cascade');

            // 5. Re-add the Foreign Key for user_id (without the unique constraint)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // 6. Create the new composite unique index
            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id']);
            $table->dropConstrainedForeignId('product_id');
            
            // Revert user_id to unique
            $table->dropForeign(['user_id']);
            $table->unique('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->boolean('is_public')->default(false);
        });
    }
};