<?php

namespace App\Console\Commands;

use App\Models\Seat;
use Illuminate\Console\Command;

class UnlockExpiredSeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seats:unlock-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock seats with expired lock time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for seats with expired locks...');

        // Find all seats that are locked but lock time has expired
        $expiredSeats = Seat::where('status', 'locked')
            ->where('locked_until', '<=', now())
            ->get();

        if ($expiredSeats->isEmpty()) {
            $this->info('No seats with expired locks found.');
            return Command::SUCCESS;
        }

        $unlockedCount = 0;

        foreach ($expiredSeats as $seat) {
            try {
                $seat->unlock();
                $this->info("Unlocked seat: {$seat->seat_number} (Schedule ID: {$seat->bus_schedule_id})");
                $unlockedCount++;
            } catch (\Exception $e) {
                $this->error("Failed to unlock seat #{$seat->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully unlocked {$unlockedCount} seat(s).");
        return Command::SUCCESS;
    }
}
