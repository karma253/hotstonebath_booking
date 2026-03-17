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
            $table->string('phone')->nullable()->after('email');
            $table->enum('role', ['guest', 'owner', 'manager', 'admin'])->default('guest')->after('phone');
            $table->enum('status', ['pending_verification', 'approved', 'rejected', 'active', 'inactive', 'deregistered'])->default('pending_verification')->after('role');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            $table->timestamp('reviewed_at')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'status', 'rejection_reason', 'approved_at', 'reviewed_at']);
        });
    }
};
