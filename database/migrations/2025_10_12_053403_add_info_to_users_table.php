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
            // Add avatar field
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('name'); // Placed after name
            }

            // Add phone number field
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email'); // Placed after email for common user info
            }

            // Add address fields
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('users', 'zip_code')) {
                $table->string('zip_code')->nullable()->after('state');
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable()->after('zip_code');
            }

            // Add role enum for customer, vendor, investor (explicitly NOT admin/super_admin)
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['customer', 'vendor', 'investor'])
                    ->default('customer')
                    ->after('country') // Place after country
                    ->index();
            } else {
                // If 'role' column already exists but needs modification
                // (e.g., adding 'investor' if it wasn't there initially)
                // This will attempt to change the enum definition.
                // Re-affirming caution with 'change()' on enums in production.
                $table->enum('role', ['customer', 'vendor', 'investor'])->change();
            }

            // Add is_active flag
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }

            // --- Optional fields (uncomment if needed) ---
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('name');
            }
        });

        // Ensure any existing users get the default 'customer' role
        // This runs after the column is added.
        if (Schema::hasColumn('users', 'role')) {
            \DB::table('users')->whereNull('role')->update(['role' => 'customer']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('users', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('users', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('users', 'zip_code')) {
                $table->dropColumn('zip_code');
            }
            if (Schema::hasColumn('users', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }

            // Reverse optional fields if they were added
            if (Schema::hasColumn('users', 'date_of_birth')) { $table->dropColumn('date_of_birth'); }
            if (Schema::hasColumn('users', 'gender')) { $table->dropColumn('gender'); }
            if (Schema::hasColumn('users', 'slug')) { $table->dropColumn('slug'); }
        });
    }
};
