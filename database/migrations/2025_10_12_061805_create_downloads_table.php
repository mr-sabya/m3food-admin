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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // The digital product
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade'); // The specific purchase
            $table->string('download_token')->unique()->nullable(); // Unique token for secure download link
            $table->integer('download_count')->default(0); // How many times this specific link has been used
            $table->timestamp('expires_at')->nullable(); // When the download link expires
            $table->string('file_path'); // Snapshot of the actual digital file path at time of purchase/download grant
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
