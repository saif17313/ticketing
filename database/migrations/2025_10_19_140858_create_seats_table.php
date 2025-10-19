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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_schedule_id')->constrained('bus_schedules')->onDelete('cascade');
            $table->string('seat_number', 10);
            $table->enum('seat_type', ['standard', 'premium', 'sleeper'])->default('standard');
            $table->enum('status', ['available', 'booked', 'locked'])->default('available');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();
            
            // Ensure each seat number is unique per bus schedule
            $table->unique(['bus_schedule_id', 'seat_number'], 'unique_seat_per_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
