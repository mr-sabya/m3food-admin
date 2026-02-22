<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1. Remove old string columns
            $table->dropColumn(['payment_method', 'shipping_method']);

            // 2. Add New Foreign Keys and Snapshots
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_method_name')->nullable()->after('payment_method_id');
            $table->string('transaction_id')->nullable()->after('payment_method_name');

            $table->foreignId('shipping_method_id')->nullable()->constrained('shipping_methods')->nullOnDelete();
            $table->string('shipping_method_name')->nullable()->after('shipping_method_id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->string('shipping_method')->nullable();

            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['payment_method_id', 'payment_method_name', 'transaction_id', 'shipping_method_id', 'shipping_method_name']);
        });
    }
};
