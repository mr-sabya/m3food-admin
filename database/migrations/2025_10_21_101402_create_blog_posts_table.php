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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // From your data: mastering-your-morning-routine
            $table->string('image_path')->nullable(); // From your data: /assets/images/blog/blog-01.png
            $table->text('excerpt')->nullable(); // Short description
            $table->longText('content')->nullable(); // Full blog post content (if you add this later)
            $table->foreignId('blog_category_id') // Foreign key to blog_categories table
                ->nullable() // Post can be uncategorized
                ->constrained() // Assumes 'blog_categories' table and 'id' column
                ->onDelete('set null'); // What to do if parent category is deleted
            $table->timestamp('published_at')->nullable(); // Your 'date' field in JS data
            $table->boolean('is_published')->default(false); // To draft/publish posts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
