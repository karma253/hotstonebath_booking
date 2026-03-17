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
        Schema::create('bath_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bath_id')->constrained('baths')->onDelete('cascade');
            $table->string('image_path');
            $table->string('image_type'); // e.g., "bath_area", "stones", "seating", "exterior", "facilities"
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bath_images');
    }
};
