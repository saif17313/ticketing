<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusCompany;
use App\Models\BusSchedule;
use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BusAndScheduleSeeder extends Seeder
{
    /**
     * Seed buses and schedules for Kamal's Green Line Paribahan
     */
    public function run(): void
    {
        // Find Kamal's user account
        $kamal = User::where('email', 'kamal@greenline.com')->first();
        
        if (!$kamal) {
            $this->command->error('❌ Kamal user not found! Run UserAndCompanySeeder first.');
            return;
        }

        // Find Kamal's company (Green Line Paribahan)
        $greenLine = BusCompany::where('owner_id', $kamal->id)->first();
        
        if (!$greenLine) {
            $this->command->error('❌ Green Line company not found for Kamal!');
            return;
        }

        $this->command->info("📦 Seeding buses for {$greenLine->name} (Owner: {$kamal->name})");

        // Get some popular routes
        $routes = Route::take(10)->get();
        
        if ($routes->isEmpty()) {
            $this->command->error('❌ No routes found! Run RouteSeeder first.');
            return;
        }

        // Create 5 buses for Green Line
        $buses = [
            [
                'bus_number' => 'GL-AC-101',
                'bus_model' => 'Volvo B11R Multi-Axle',
                'bus_type' => 'AC',
                'total_seats' => 40,
                'seat_layout' => '2x2',
                'amenities' => 'WiFi,USB Charging,Water,Blanket,Reading Light',
            ],
            [
                'bus_number' => 'GL-AC-102',
                'bus_model' => 'Scania K360IB',
                'bus_type' => 'AC',
                'total_seats' => 36,
                'seat_layout' => '2x1',
                'amenities' => 'WiFi,USB Charging,Water,Blanket,Snacks,TV',
            ],
            [
                'bus_number' => 'GL-NAC-101',
                'bus_model' => 'Ashok Leyland Viking',
                'bus_type' => 'Non-AC',
                'total_seats' => 52,
                'seat_layout' => '2x2',
                'amenities' => 'USB Charging,Water',
            ],
            [
                'bus_number' => 'GL-AC-103',
                'bus_model' => 'Hino AK1J',
                'bus_type' => 'AC',
                'total_seats' => 42,
                'seat_layout' => '2x2',
                'amenities' => 'WiFi,USB Charging,Water,TV',
            ],
            [
                'bus_number' => 'GL-NAC-102',
                'bus_model' => 'Tata LP 1512',
                'bus_type' => 'Non-AC',
                'total_seats' => 48,
                'seat_layout' => '2x2',
                'amenities' => 'USB Charging',
            ],
        ];

        $createdBuses = [];
        foreach ($buses as $busData) {
            // Check if bus already exists
            $existingBus = Bus::where('bus_number', $busData['bus_number'])->first();
            
            if ($existingBus) {
                $this->command->warn("  ⚠️  Bus {$busData['bus_number']} already exists, skipping...");
                $createdBuses[] = $existingBus;
                continue;
            }
            
            $bus = Bus::create([
                'company_id' => $greenLine->id,
                'route_id' => $routes->random()->id, // Assign random route
                'bus_number' => $busData['bus_number'],
                'bus_model' => $busData['bus_model'],
                'bus_type' => $busData['bus_type'],
                'total_seats' => $busData['total_seats'],
                'seat_layout' => $busData['seat_layout'],
                'amenities' => $busData['amenities'],
                'is_active' => true,
            ]);
            
            $createdBuses[] = $bus;
            $this->command->info("  ✅ Created bus: {$bus->bus_number} ({$bus->bus_type})");
        }

        // Create schedules for the next 7 days
        $this->command->info("📅 Creating schedules for next 7 days...");
        
        $today = Carbon::today();
        $schedulesCreated = 0;

        foreach ($createdBuses as $bus) {
            // Create 2-3 schedules per day for each bus
            $schedulesPerDay = $bus->bus_type === 'AC' ? 3 : 2;
            
            for ($day = 0; $day < 7; $day++) {
                $journeyDate = $today->copy()->addDays($day);
                
                // Different departure times based on bus type
                $departureTimes = $bus->bus_type === 'AC' 
                    ? ['08:00:00', '14:30:00', '22:00:00'] 
                    : ['06:30:00', '18:00:00'];
                
                for ($i = 0; $i < $schedulesPerDay; $i++) {
                    $departureTime = $departureTimes[$i];
                    
                    // Calculate arrival time (add route duration)
                    $route = Route::find($bus->route_id);
                    $arrivalTime = Carbon::parse($departureTime)
                        ->addMinutes($route->estimated_duration_minutes)
                        ->format('H:i:s');
                    
                    // Base fare varies by bus type
                    $baseFare = $bus->bus_type === 'AC' 
                        ? rand(800, 1500) 
                        : rand(400, 800);

                    BusSchedule::create([
                        'bus_id' => $bus->id,
                        'journey_date' => $journeyDate->format('Y-m-d'),
                        'departure_time' => $departureTime,
                        'arrival_time' => $arrivalTime,
                        'base_fare' => $baseFare,
                        'available_seats' => $bus->total_seats,
                    ]);
                    
                    $schedulesCreated++;
                }
            }
        }

        $this->command->info("✅ Created {$schedulesCreated} schedules for " . count($createdBuses) . " buses");
        $this->command->info("🎉 Bus and Schedule seeding complete!");
        
        // Display summary
        $this->command->table(
            ['Company', 'Owner', 'Buses', 'Schedules'],
            [[
                $greenLine->name,
                $kamal->email,
                count($createdBuses),
                $schedulesCreated
            ]]
        );
    }
}
