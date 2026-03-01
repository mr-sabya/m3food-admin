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
        Schema::create('delivery_partners', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('custom'); // e.g., Custom, Steadfast, Pathao
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable(); // From the Location search input

            // Relationship
            $table->foreignId('shop_warehouse_id')->constrained()->onDelete('cascade');

            // Delivery Fees (What you charge the customer)
            // Storing Regular and Express as separate columns or as a JSON for flexibility
            $table->decimal('fee_inside', 10, 2)->default(0);
            $table->decimal('fee_outside', 10, 2)->default(0);
            $table->decimal('fee_suburb', 10, 2)->default(0);

            // Express Fees
            $table->decimal('express_fee_inside', 10, 2)->default(0);
            $table->decimal('express_fee_outside', 10, 2)->default(0);
            $table->decimal('express_fee_suburb', 10, 2)->default(0);

            // Operational Costs (What the courier charges you)
            $table->decimal('courier_delivery_cost', 10, 2)->default(0);
            $table->decimal('courier_return_cost', 10, 2)->default(0);

            // COD Charge
            $table->decimal('cod_charge_percent', 5, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_partners');
    }
};
