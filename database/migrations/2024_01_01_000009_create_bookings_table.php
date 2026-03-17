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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->unique(); // Booking ID (e.g., BOOKING-20240115-00001)
            $table->foreignId('guest_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bath_id')->constrained('baths')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('bath_services')->onDelete('cascade');
            
            // Guest Details
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            
            // Booking Details
            $table->dateTime('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('number_of_guests');
            $table->decimal('total_price', 10, 2);
            
            // Payment
            $table->enum('payment_method', ['online', 'on_site'])->default('on_site');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            
            // Booking Status
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'no_show'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
