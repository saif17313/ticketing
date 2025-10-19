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
        Schema::create('bus_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');
            $table->date('journey_date');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->decimal('base_fare', 8, 2);
            $table->integer('available_seats');
            $table->enum('status', ['scheduled', 'departed', 'arrived', 'cancelled'])->default('scheduled');
            $table->timestamps();
            
            // Prevent duplicate schedules for same bus on same date/time
            $table->unique(['bus_id', 'journey_date', 'departure_time'], 'unique_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_schedules');
    }
};
