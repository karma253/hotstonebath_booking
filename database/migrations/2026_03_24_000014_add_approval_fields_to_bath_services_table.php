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
        Schema::table('bath_services', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('is_available');
            $table->text('approval_notes')->nullable()->after('approval_status');
            $table->timestamp('reviewed_at')->nullable()->after('approval_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bath_services', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'approval_notes', 'reviewed_at']);
        });
    }
};
