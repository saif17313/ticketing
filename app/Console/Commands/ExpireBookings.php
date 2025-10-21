<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire bookings that have passed their expiry time and release seats';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for expired bookings...');

        // Find all pending bookings that have expired
        $expiredBookings = Booking::where('status', 'pending')
            ->where('booking_type', 'book') // Only 'book' type has expiry
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('✅ No expired bookings found.');
            return 0;
        }

        $this->info("⏰ Found {$expiredBookings->count()} expired booking(s). Processing...");

        $successCount = 0;
        $failCount = 0;

        foreach ($expiredBookings as $booking) {
            DB::beginTransaction();
            try {
                // Release all seats
                $seats = $booking->seats;
                foreach ($seats as $seat) {
                    $seat->release();
                }

                // Update schedule available seats
                $booking->busSchedule->increment('available_seats', $seats->count());

                // Mark booking as expired
                $booking->update(['status' => 'expired']);

                // Log the expiry
                Log::info("Booking {$booking->booking_reference} expired and {$seats->count()} seat(s) released.");

                DB::commit();
                $successCount++;

                $this->line("  ✓ Booking {$booking->booking_reference} - {$seats->count()} seat(s) released");
            } catch (\Exception $e) {
                DB::rollBack();
                $failCount++;
                
                Log::error("Failed to expire booking {$booking->booking_reference}: " . $e->getMessage());
                $this->error("  ✗ Failed to expire booking {$booking->booking_reference}");
            }
        }

        $this->info("\n📊 Summary:");
        $this->info("  ✅ Successfully expired: {$successCount}");
        if ($failCount > 0) {
            $this->error("  ❌ Failed to expire: {$failCount}");
        }
        $this->info("  📅 " . now()->format('Y-m-d H:i:s'));

        return 0;
    }
}
