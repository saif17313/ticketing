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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('transaction_id', 100)->unique()->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'bkash', 'nagad', 'rocket', 'card', 'bank_transfer']);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('invoice_number', 50)->unique()->nullable();
            $table->string('invoice_path')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('payment_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
