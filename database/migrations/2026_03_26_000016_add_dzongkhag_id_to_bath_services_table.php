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
            $table->foreignId('dzongkhag_id')
                ->nullable()
                ->after('bath_id')
                ->constrained('dzongkhags')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bath_services', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dzongkhag_id');
        });
    }
};
