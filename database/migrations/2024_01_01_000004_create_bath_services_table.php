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
        Schema::create('bath_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bath_id')->constrained('baths')->onDelete('cascade');
            $table->string('service_type'); // e.g., "Standard Bath", "Luxury SPA", "Family Package"
            $table->text('description')->nullable();
            $table->integer('duration_minutes'); // Duration in minutes
            $table->decimal('price', 10, 2);
            $table->integer('max_guests'); // Max guests for this service
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bath_services');
    }
};
