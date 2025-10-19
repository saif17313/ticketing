<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * This runs all seeders in the correct order
     */
    public function run(): void
    {
        // Run seeders in order (respecting foreign key constraints)
        $this->call([
            DistrictSeeder::class,          // 1. Seed all 64 districts first
            UserAndCompanySeeder::class,    // 2. Create owner users and bus companies
            RouteSeeder::class,             // 3. Create routes (depends on districts)
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📍 64 districts added');
        $this->command->info('👥 Sample users and companies created');
        $this->command->info('🛣️  Popular routes created');
    }
}

