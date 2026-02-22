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
        Schema::create('investor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique(); // One-to-one with user
            $table->string('company_name')->nullable();
            $table->string('investment_focus')->nullable(); // e.g., "Tech Startups", "Real Estate", "Ecommerce"
            $table->string('website')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();

            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade'); // Foreign key to countries table
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('cascade'); // Foreign key to states table
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('cascade'); // Foreign key to cities table

            $table->decimal('min_investment_amount', 15, 2)->nullable();
            $table->decimal('max_investment_amount', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_profiles');
    }
};
