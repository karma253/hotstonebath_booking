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
        Schema::create('baths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('property_type', ['hot_stone_bath', 'hot_spring', 'thermal_pool'])->default('hot_stone_bath');
            $table->foreignId('dzongkhag_id')->constrained('dzongkhags');
            $table->text('full_address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('short_description');
            $table->longText('detailed_description')->nullable();
            
            // Legal Details
            $table->string('tourism_license_number');
            $table->string('issuing_authority');
            $table->date('license_issue_date');
            $table->date('license_expiry_date');
            $table->enum('license_status', ['valid', 'expired', 'pending'])->default('pending');
            
            // Bath Details
            $table->integer('max_guests')->default(10);
            $table->decimal('price_per_hour', 10, 2)->default(0);
            $table->enum('booking_type', ['instant', 'approval_required'])->default('approval_required');
            $table->text('cancellation_policy')->nullable();
            
            // Status
            $table->enum('status', ['pending_verification', 'active', 'inactive', 'suspended'])->default('pending_verification');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baths');
    }
};
