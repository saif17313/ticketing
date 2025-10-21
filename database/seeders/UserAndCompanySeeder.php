<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAndCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates sample owner users and their bus companies
     */
    public function run(): void
    {
        // Create admin user
        DB::table('users')->insert([
            'name' => 'System Administrator',
            'email' => 'admin@bdbus.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '01700000000',
            'address' => 'System Admin',
            'nid' => '0000000000000',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create sample passenger user for testing
        $passengerId = DB::table('users')->insertGetId([
            'name' => 'Ahmed Hassan',
            'email' => 'ahmed@example.com',
            'password' => Hash::make('password'),
            'role' => 'passenger',
            'phone' => '01712345678',
            'address' => 'Dhanmondi, Dhaka-1209',
            'nid' => '1234567890123',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create sample owner users
        $owner1Id = DB::table('users')->insertGetId([
            'name' => 'Kamal Hossain',
            'email' => 'kamal@greenline.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '01912345678',
            'address' => 'Motijheel, Dhaka',
            'nid' => '9876543210987',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $owner2Id = DB::table('users')->insertGetId([
            'name' => 'Rahim Uddin',
            'email' => 'rahim@hanif.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '01812345678',
            'address' => 'Gulshan, Dhaka',
            'nid' => '5432167890123',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $owner3Id = DB::table('users')->insertGetId([
            'name' => 'Salim Ahmed',
            'email' => 'salim@shohagh.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '01612345678',
            'address' => 'Banani, Dhaka',
            'nid' => '1357924680123',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create bus companies
        DB::table('bus_companies')->insert([
            [
                'owner_id' => $owner1Id,
                'name' => 'Green Line Paribahan',
                'logo' => 'logos/greenline.png',
                'email' => 'info@greenline.com',
                'phone' => '09613111111',
                'address' => 'Kallyanpur, Dhaka-1207',
                'license_number' => 'GL-DHK-2020-001',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $owner2Id,
                'name' => 'Hanif Enterprise',
                'logo' => 'logos/hanif.png',
                'email' => 'info@hanif.com',
                'phone' => '09611111111',
                'address' => 'Jatrabari, Dhaka-1204',
                'license_number' => 'HE-DHK-2019-002',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $owner3Id,
                'name' => 'Shohagh Paribahan',
                'logo' => 'logos/shohagh.png',
                'email' => 'info@shohagh.com',
                'phone' => '09666777888',
                'address' => 'Gabtoli, Dhaka-1216',
                'license_number' => 'SP-DHK-2021-003',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

