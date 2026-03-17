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
        Schema::create('bath_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bath_id')->constrained('baths')->onDelete('cascade');
            $table->string('facility_name'); // e.g., "Changing Room", "Shower", "Towels", etc.
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bath_facilities');
    }
};
