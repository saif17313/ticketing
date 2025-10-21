<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:release-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release seats and mark bookings as expired when payment deadline passes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired bookings...');

        // Find all pending bookings with passed payment deadline
        $expiredBookings = Booking::where('status', 'pending')
            ->where('payment_deadline', '<=', now())
            ->whereNotNull('payment_deadline')
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('No expired bookings found.');
            return Command::SUCCESS;
        }

        $releasedCount = 0;

        foreach ($expiredBookings as $booking) {
            DB::beginTransaction();
            try {
                // Release all seats for this booking
                $seats = $booking->seats;
                foreach ($seats as $seat) {
                    $seat->release();
                }

                // Update schedule available seats
                $booking->busSchedule->increment('available_seats', $seats->count());

                // Mark booking as expired
                $booking->update(['status' => 'expired']);

                $this->info("Released booking #{$booking->id} - Reference: {$booking->booking_reference}");
                $releasedCount++;

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to release booking #{$booking->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully released {$releasedCount} expired booking(s).");
        return Command::SUCCESS;
    }
}
