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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique identifier for the setting (e.g., 'site_name', 'admin_email')
            $table->longText('value')->nullable(); // The value of the setting (can be long text, JSON, etc.)
            $table->enum('type', ['string', 'integer', 'boolean', 'json', 'text', 'email', 'url', 'image', 'color', 'password'])->default('string'); // Helps with casting and admin UI
            $table->text('description')->nullable(); // Explains what the setting does for admin users
            $table->string('group')->nullable()->index(); // For grouping settings in the admin panel (e.g., 'General', 'SEO', 'Social')
            $table->boolean('is_private')->default(false); // If true, this setting might contain sensitive data (like API keys) and should be handled carefully.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
