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

        // Create multiple owner users for different companies
        $owners = [
            [
                'name' => 'Kamal Hossain',
                'email' => 'kamal@greenline.com',
                'phone' => '01912345678',
                'address' => 'Motijheel, Dhaka',
                'nid' => '9876543210987',
            ],
            [
                'name' => 'Rahim Uddin',
                'email' => 'rahim@hanif.com',
                'phone' => '01812345678',
                'address' => 'Gulshan, Dhaka',
                'nid' => '5432167890123',
            ],
            [
                'name' => 'Salim Ahmed',
                'email' => 'salim@shohagh.com',
                'phone' => '01612345678',
                'address' => 'Banani, Dhaka',
                'nid' => '1357924680123',
            ],
            [
                'name' => 'Jamal Uddin',
                'email' => 'jamal@shyamoli.com',
                'phone' => '01512345678',
                'address' => 'Mirpur, Dhaka',
                'nid' => '2468013579123',
            ],
            [
                'name' => 'Bashir Ahmed',
                'email' => 'bashir@ena.com',
                'phone' => '01412345678',
                'address' => 'Uttara, Dhaka',
                'nid' => '3691470258123',
            ],
            [
                'name' => 'Kabir Hossain',
                'email' => 'kabir@saudia.com',
                'phone' => '01312345678',
                'address' => 'Mohammadpur, Dhaka',
                'nid' => '7412589630123',
            ],
            [
                'name' => 'Nasir Khan',
                'email' => 'nasir@royalcoach.com',
                'phone' => '01212345678',
                'address' => 'Tejgaon, Dhaka',
                'nid' => '8523697410123',
            ],
        ];

        $ownerIds = [];
        foreach ($owners as $owner) {
            $ownerIds[] = DB::table('users')->insertGetId([
                'name' => $owner['name'],
                'email' => $owner['email'],
                'password' => Hash::make('password'),
                'role' => 'owner',
                'phone' => $owner['phone'],
                'address' => $owner['address'],
                'nid' => $owner['nid'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create bus companies
        DB::table('bus_companies')->insert([
            [
                'owner_id' => $ownerIds[0],
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
                'owner_id' => $ownerIds[1],
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
                'owner_id' => $ownerIds[2],
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
            [
                'owner_id' => $ownerIds[3],
                'name' => 'Shyamoli Paribahan',
                'logo' => 'logos/shyamoli.png',
                'email' => 'info@shyamoli.com',
                'phone' => '09666888999',
                'address' => 'Kalabagan, Dhaka-1205',
                'license_number' => 'SY-DHK-2018-004',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $ownerIds[4],
                'name' => 'Ena Paribahan',
                'logo' => 'logos/ena.png',
                'email' => 'info@ena.com',
                'phone' => '09639666888',
                'address' => 'Sayedabad, Dhaka-1203',
                'license_number' => 'EN-DHK-2020-005',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $ownerIds[5],
                'name' => 'Saudia Paribahan',
                'logo' => 'logos/saudia.png',
                'email' => 'info@saudia.com',
                'phone' => '09610888999',
                'address' => 'Abdullahpur, Dhaka-1230',
                'license_number' => 'SD-DHK-2019-006',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner_id' => $ownerIds[6],
                'name' => 'Royal Coach',
                'logo' => 'logos/royalcoach.png',
                'email' => 'info@royalcoach.com',
                'phone' => '09603777888',
                'address' => 'Farmgate, Dhaka-1215',
                'license_number' => 'RC-DHK-2021-007',
                'is_verified' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✅ Created 7 bus companies with owners');
    }
}

