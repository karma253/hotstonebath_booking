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
        // Add fields to users table for CID and ID proof (with existence check)
        if (!Schema::hasColumn('users', 'cid')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('cid')->nullable()->after('phone')->comment('Citizen ID Number');
            });
        }
        
        if (!Schema::hasColumn('users', 'id_proof_path')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('id_proof_path')->nullable()->after('cid')->comment('Path to uploaded ID proof document');
            });
        }

        // Add fields to baths table for bath type and available days
        if (!Schema::hasColumn('baths', 'bath_type')) {
            Schema::table('baths', function (Blueprint $table) {
                $table->enum('bath_type', ['menchu', 'dotsho', 'tshachu'])->default('menchu')->after('property_type')->comment('Traditional Bhutanese bath type');
            });
        }
        
        if (!Schema::hasColumn('baths', 'available_days')) {
            Schema::table('baths', function (Blueprint $table) {
                $table->json('available_days')->default(json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']))->after('cancellation_policy')->comment('Available days of week in JSON format');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'cid')) {
                $table->dropColumn('cid');
            }
            if (Schema::hasColumn('users', 'id_proof_path')) {
                $table->dropColumn('id_proof_path');
            }
        });

        Schema::table('baths', function (Blueprint $table) {
            if (Schema::hasColumn('baths', 'bath_type')) {
                $table->dropColumn('bath_type');
            }
            if (Schema::hasColumn('baths', 'available_days')) {
                $table->dropColumn('available_days');
            }
        });
    }
};
