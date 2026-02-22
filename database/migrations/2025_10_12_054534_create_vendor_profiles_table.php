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
        Schema::create('vendor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade'); // Link to User
            $table->string('shop_name')->unique();
            $table->string('shop_slug')->unique(); // For /shop/your-shop-name URLs
            $table->text('shop_description')->nullable();
            $table->string('shop_logo')->nullable(); // Path to logo image
            $table->string('shop_banner')->nullable(); // Path to banner image
            $table->string('phone')->nullable(); // Vendor-specific contact, can be different from user's
            $table->string('email')->nullable(); // Vendor-specific contact
            $table->text('address')->nullable(); // Vendor's business address
            $table->string('zip_code')->nullable();

            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('cascade'); // Foreign key to countries table
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('cascade'); // Foreign key to states table
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('cascade'); // Foreign key to cities table
            
            // Banking details for payouts
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            // Could add IBAN, Swift/BIC for international

            $table->decimal('commission_rate', 5, 2)->nullable(); // Overrides global commission
            $table->boolean('is_approved')->default(false); // Admin approval for new vendors
            $table->boolean('is_active')->default(true); // Vendor status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_profiles');
    }
};
