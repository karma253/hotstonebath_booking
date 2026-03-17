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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bath_id')->constrained('baths')->onDelete('cascade');
            $table->integer('day_of_week'); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            $table->time('opening_time');
            $table->time('closing_time');
            $table->boolean('is_open')->default(true);
            $table->timestamps();
            
            $table->unique(['bath_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
