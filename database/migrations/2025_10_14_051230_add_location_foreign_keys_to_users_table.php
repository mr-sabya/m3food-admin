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
        Schema::table('users', function (Blueprint $table) {
            // Drop existing columns if they exist.
            // Wrap in Schema::hasColumn to prevent errors if running on a fresh database
            // or if the columns were already dropped.
            if (Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('users', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('users', 'country')) {
                $table->dropColumn('country');
            }

            // Add new foreign key columns
            // Ensure these tables (countries, states, cities) exist BEFORE running this migration.
            $table->foreignId('country_id')
                ->nullable()
                ->after('zip_code') // Place after zip_code or wherever makes sense
                ->constrained('countries')
                ->onDelete('set null');

            $table->foreignId('state_id')
                ->nullable()
                ->after('country_id')
                ->constrained('states')
                ->onDelete('set null');

            $table->foreignId('city_id')
                ->nullable()
                ->after('state_id')
                ->constrained('cities')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key columns first
            $table->dropConstrainedForeignId('city_id');
            $table->dropConstrainedForeignId('state_id');
            $table->dropConstrainedForeignId('country_id');

            // Re-add the original string columns if you want to revert fully.
            // Be mindful that data from the original string columns would have been lost
            // if you didn't back it up or migrate it when going "up".
            $table->string('city')->nullable()->after('zip_code');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
        });
    }
};
