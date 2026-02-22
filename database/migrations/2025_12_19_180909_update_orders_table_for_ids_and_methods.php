<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1. Remove old string columns if they still exist
            $table->dropColumn([
                'shipping_country',
                'shipping_state',
                'shipping_city',
                'billing_country',
                'billing_state',
                'billing_city'
            ]);

            // 2. Add Shipping ID columns
            $table->foreignId('shipping_country_id')->nullable()->after('shipping_phone')->constrained('countries');
            $table->foreignId('shipping_state_id')->nullable()->after('shipping_country_id')->constrained('states');
            $table->foreignId('shipping_city_id')->nullable()->after('shipping_state_id')->constrained('cities');

            // 3. Add Billing ID columns
            $table->foreignId('billing_country_id')->nullable()->after('billing_phone')->constrained('countries');
            $table->foreignId('billing_state_id')->nullable()->after('billing_country_id')->constrained('states');
            $table->foreignId('billing_city_id')->nullable()->after('billing_state_id')->constrained('cities');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['shipping_country_id', 'shipping_state_id', 'shipping_city_id']);
            $table->dropForeign(['billing_country_id', 'billing_state_id', 'billing_city_id']);

            // Remove columns
            $table->dropColumn([
                'shipping_country_id',
                'shipping_state_id',
                'shipping_city_id',
                'billing_country_id',
                'billing_state_id',
                'billing_city_id'
            ]);

            // Restore string columns
            $table->string('shipping_country')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_city')->nullable();
        });
    }
};
