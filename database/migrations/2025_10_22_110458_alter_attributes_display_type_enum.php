<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AttributeDisplayType; // Import your enum

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the values from your PHP enum
        $enumCases = array_map(fn($case) => $case->value, AttributeDisplayType::cases());

        // Alter the column to use the new ENUM values
        Schema::table('attributes', function (Blueprint $table) use ($enumCases) {
            $table->enum('display_type', $enumCases)
                ->default(AttributeDisplayType::Text->value) // Set default to match enum
                ->change(); // Important: indicates altering an existing column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the old ENUM values if necessary, or just a generic string.
        // Reverting ENUM types can be tricky if data doesn't fit the old enum.
        // For simplicity, we might just revert to a string or a more generic enum.
        Schema::table('attributes', function (Blueprint $table) {
            $table->string('display_type', 50)->default('text')->change(); // Revert to a generic string type
            // Or if you want to explicitly revert to the *old* enum:
            // $table->enum('display_type', ['text', 'color_swatch', 'image_swatch', 'dropdown', 'radio', 'checkbox'])->default('text')->change();
        });
    }
};
