<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\BusSchedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚌 Creating bus schedules...');

        // Get all active buses
        $buses = Bus::where('is_active', true)->get();

        if ($buses->isEmpty()) {
            $this->command->warn('⚠️  No buses found! Please create buses first.');
            return;
        }

        foreach ($buses as $bus) {
            // Create schedules for next 7 days
            for ($i = 0; $i < 7; $i++) {
                $journeyDate = Carbon::today()->addDays($i);
                
                // Create multiple schedules throughout the day
                $schedules = [
                    ['departure' => '06:00:00', 'arrival' => '12:00:00'], // Early morning
                    ['departure' => '08:00:00', 'arrival' => '14:00:00'], // Morning
                    ['departure' => '10:00:00', 'arrival' => '16:00:00'], // Late morning
                    ['departure' => '12:00:00', 'arrival' => '18:00:00'], // Noon
                    ['departure' => '14:00:00', 'arrival' => '20:00:00'], // Afternoon
                    ['departure' => '16:00:00', 'arrival' => '22:00:00'], // Evening
                    ['departure' => '18:00:00', 'arrival' => '00:00:00'], // Late evening
                    ['departure' => '22:00:00', 'arrival' => '04:00:00'], // Night
                ];

                foreach ($schedules as $schedule) {
                    BusSchedule::create([
                        'bus_id' => $bus->id,
                        'journey_date' => $journeyDate->format('Y-m-d'),
                        'departure_time' => $schedule['departure'],
                        'arrival_time' => $schedule['arrival'],
                        'base_fare' => $bus->bus_type === 'AC' ? 
                            (in_array($schedule['departure'], ['06:00:00', '22:00:00']) ? 900.00 : 800.00) : 
                            (in_array($schedule['departure'], ['06:00:00', '22:00:00']) ? 700.00 : 600.00),
                        'available_seats' => $bus->total_seats,
                        'status' => 'scheduled',
                    ]);
                }
            }

            $schedulesCount = 7 * 8; // 7 days * 8 schedules per day
            $this->command->info("✅ Created {$schedulesCount} schedules for bus: {$bus->bus_number}");
        }

        $totalSchedules = BusSchedule::count();
        $this->command->info("✅ Total schedules created: {$totalSchedules}");
    }
}
