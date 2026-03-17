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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('guest_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bath_id')->constrained('baths')->onDelete('cascade');
            $table->integer('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->unique(['booking_id', 'guest_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
