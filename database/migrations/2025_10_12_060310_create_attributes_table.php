<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\AttributeDisplayType; // Ensure this Enum exists

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the values directly from your PHP enum cases
        $enumCases = array_map(fn($case) => $case->value, AttributeDisplayType::cases());

        Schema::create('attributes', function (Blueprint $table) use ($enumCases) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Color", "Size"
            $table->string('slug')->unique(); // Internal identification

            // Define the enum column using your Enum class values
            $table->enum('display_type', $enumCases)
                ->default(AttributeDisplayType::Text->value);

            $table->boolean('is_filterable')->default(true); // Used in frontend sidebar filters
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
