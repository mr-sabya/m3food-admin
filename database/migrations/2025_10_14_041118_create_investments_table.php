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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_profile_id')->constrained('investor_profiles')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null'); // Optional, assuming a 'projects' table
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD'); // e.g., 'USD', 'EUR', 'GBP'
            $table->date('investment_date');
            $table->string('status')->default('pending'); // e.g., 'pending', 'committed', 'funded', 'returned', 'failed'
            $table->decimal('return_on_investment', 8, 2)->nullable(); // Could be a percentage (e.g., 10.50 for 10.5%)
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
