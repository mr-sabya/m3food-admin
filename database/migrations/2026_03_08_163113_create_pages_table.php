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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            // Identity & Type
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('page_type'); // home, shop, landing_page, etc.
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            // Layout Architecture
            $table->foreignId('theme_id')->nullable()->constrained('themes')->nullOnDelete();
            $table->foreignId('header_id')->nullable()->constrained('headers')->nullOnDelete();
            $table->foreignId('footer_id')->nullable()->constrained('footers')->nullOnDelete();
            $table->boolean('is_header_visible')->default(true);
            $table->boolean('is_footer_visible')->default(true);

            // Advanced SEO (JSON for cleaner structure)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('meta_robots')->default('index, follow');

            // Commerce & Conversion
            $table->foreignId('main_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->json('upsell_product_ids')->nullable();
            $table->boolean('show_checkout_form')->default(false);
            $table->string('checkout_form_title')->nullable();

            // Urgency Logic (Timer)
            $table->boolean('show_timer')->default(false);
            $table->string('timer_label')->nullable();
            $table->timestamp('timer_ends_at')->nullable();

            // The Builder Core
            $table->json('content')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
