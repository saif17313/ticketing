<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusCompany;
use App\Models\Route;
use App\Models\Bus;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚌 Creating buses for all companies...');

        $companies = BusCompany::all();
        $routes = Route::all();

        if ($companies->isEmpty()) {
            $this->command->warn('⚠️  No companies found! Please run UserAndCompanySeeder first.');
            return;
        }

        if ($routes->isEmpty()) {
            $this->command->warn('⚠️  No routes found! Please run RouteSeeder first.');
            return;
        }

        $busCount = 0;

        // For each company, create buses for each route
        foreach ($companies as $company) {
            $companyShortName = $this->getCompanyShortName($company->name);
            
            // Create 3-4 buses per route for each company (mix of AC and Non-AC)
            foreach ($routes as $route) {
                // AC Bus 1
                Bus::create([
                    'company_id' => $company->id,
                    'route_id' => $route->id,
                    'bus_number' => $companyShortName . '-AC-' . str_pad($busCount + 1, 3, '0', STR_PAD_LEFT),
                    'bus_model' => 'Volvo B11R',
                    'bus_type' => 'AC',
                    'total_seats' => 40,
                    'amenities' => json_encode(['WiFi', 'AC', 'Charging Port', 'Reading Light', 'Blanket']),
                    'is_active' => true,
                ]);
                $busCount++;

                // Non-AC Bus 1
                Bus::create([
                    'company_id' => $company->id,
                    'route_id' => $route->id,
                    'bus_number' => $companyShortName . '-NAC-' . str_pad($busCount + 1, 3, '0', STR_PAD_LEFT),
                    'bus_model' => 'Ashok Leyland',
                    'bus_type' => 'Non-AC',
                    'total_seats' => 44,
                    'amenities' => json_encode(['Comfortable Seats', 'Reading Light']),
                    'is_active' => true,
                ]);
                $busCount++;
            }

            $this->command->info("✅ Created buses for: {$company->name}");
        }

        $this->command->info("✅ Total buses created: {$busCount}");
    }

    private function getCompanyShortName($name): string
    {
        $shortNames = [
            'Green Line Paribahan' => 'GL',
            'Hanif Enterprise' => 'HE',
            'Shohagh Paribahan' => 'SH',
            'Shyamoli Paribahan' => 'SY',
            'Ena Paribahan' => 'EN',
            'Saudia Paribahan' => 'SD',
            'Royal Coach' => 'RC',
        ];

        return $shortNames[$name] ?? strtoupper(substr($name, 0, 2));
    }
}
