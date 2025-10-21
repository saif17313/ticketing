<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates popular bus routes from Dhaka to major cities
     */
    public function run(): void
    {
        // Helper function to get district ID by name
        $getDistrictId = function($name) {
            return DB::table('districts')->where('name', $name)->value('id');
        };

        $routes = [
            // Popular routes from Dhaka
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Chittagong'),
                'distance_km' => 264.00,
                'estimated_duration_minutes' => 360, // 6 hours
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Khulna'),
                'distance_km' => 278.50,
                'estimated_duration_minutes' => 420, // 7 hours
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Sylhet'),
                'distance_km' => 242.00,
                'estimated_duration_minutes' => 360, // 6 hours
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Rajshahi'),
                'distance_km' => 256.00,
                'estimated_duration_minutes' => 390, // 6.5 hours
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Barisal'),
                'distance_km' => 198.00,
                'estimated_duration_minutes' => 300, // 5 hours
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId("Cox's Bazar"),
                'distance_km' => 390.00,
                'estimated_duration_minutes' => 600, // 10 hours
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Rangpur'),
                'distance_km' => 304.00,
                'estimated_duration_minutes' => 450, // 7.5 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Cumilla'),
                'distance_km' => 97.00,
                'estimated_duration_minutes' => 120, // 2 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Bogura'),
                'distance_km' => 220.00,
                'estimated_duration_minutes' => 330, // 5.5 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Jashore'),
                'distance_km' => 171.00,
                'estimated_duration_minutes' => 270, // 4.5 hours
                'is_popular' => false,
                'is_active' => true,
            ],

            // More Dhaka routes
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Dinajpur'),
                'distance_km' => 402.00,
                'estimated_duration_minutes' => 540, // 9 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Mymensingh'),
                'distance_km' => 120.00,
                'estimated_duration_minutes' => 180, // 3 hours
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Tangail'),
                'distance_km' => 103.00,
                'estimated_duration_minutes' => 150, // 2.5 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Feni'),
                'distance_km' => 162.00,
                'estimated_duration_minutes' => 240, // 4 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Noakhali'),
                'distance_km' => 152.00,
                'estimated_duration_minutes' => 240, // 4 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Dhaka'),
                'destination_district_id' => $getDistrictId('Patuakhali'),
                'distance_km' => 239.00,
                'estimated_duration_minutes' => 360, // 6 hours
                'is_popular' => false,
                'is_active' => true,
            ],

            // Reverse routes (return journeys from major cities)
            [
                'source_district_id' => $getDistrictId('Chittagong'),
                'destination_district_id' => $getDistrictId('Dhaka'),
                'distance_km' => 264.00,
                'estimated_duration_minutes' => 360,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Khulna'),
                'destination_district_id' => $getDistrictId('Dhaka'),
                'distance_km' => 278.50,
                'estimated_duration_minutes' => 420,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Sylhet'),
                'destination_district_id' => $getDistrictId('Dhaka'),
                'distance_km' => 242.00,
                'estimated_duration_minutes' => 360,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Rajshahi'),
                'destination_district_id' => $getDistrictId('Dhaka'),
                'distance_km' => 256.00,
                'estimated_duration_minutes' => 390,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Barisal'),
                'destination_district_id' => $getDistrictId('Dhaka'),
                'distance_km' => 198.00,
                'estimated_duration_minutes' => 300,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId("Cox's Bazar"),
                'destination_district_id' => $getDistrictId('Dhaka'),
                'distance_km' => 390.00,
                'estimated_duration_minutes' => 600,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Rangpur'),
                'destination_district_id' => $getDistrictId('Dhaka'),
                'distance_km' => 304.00,
                'estimated_duration_minutes' => 450,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Mymensingh'),
                'destination_district_id' => $getDistrictId('Dhaka'),
                'distance_km' => 120.00,
                'estimated_duration_minutes' => 180,
                'is_popular' => true,
                'is_active' => true,
            ],

            // Chittagong to other cities
            [
                'source_district_id' => $getDistrictId('Chittagong'),
                'destination_district_id' => $getDistrictId("Cox's Bazar"),
                'distance_km' => 152.00,
                'estimated_duration_minutes' => 240, // 4 hours
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId("Cox's Bazar"),
                'destination_district_id' => $getDistrictId('Chittagong'),
                'distance_km' => 152.00,
                'estimated_duration_minutes' => 240,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Chittagong'),
                'destination_district_id' => $getDistrictId('Sylhet'),
                'distance_km' => 238.00,
                'estimated_duration_minutes' => 360, // 6 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Sylhet'),
                'destination_district_id' => $getDistrictId('Chittagong'),
                'distance_km' => 238.00,
                'estimated_duration_minutes' => 360,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Chittagong'),
                'destination_district_id' => $getDistrictId('Cumilla'),
                'distance_km' => 97.00,
                'estimated_duration_minutes' => 120, // 2 hours
                'is_popular' => false,
                'is_active' => true,
            ],

            // Khulna to other cities
            [
                'source_district_id' => $getDistrictId('Khulna'),
                'destination_district_id' => $getDistrictId('Jashore'),
                'distance_km' => 70.00,
                'estimated_duration_minutes' => 90, // 1.5 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Jashore'),
                'destination_district_id' => $getDistrictId('Khulna'),
                'distance_km' => 70.00,
                'estimated_duration_minutes' => 90,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Khulna'),
                'destination_district_id' => $getDistrictId('Barisal'),
                'distance_km' => 138.00,
                'estimated_duration_minutes' => 210, // 3.5 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Barisal'),
                'destination_district_id' => $getDistrictId('Khulna'),
                'distance_km' => 138.00,
                'estimated_duration_minutes' => 210,
                'is_popular' => false,
                'is_active' => true,
            ],

            // Rajshahi to other cities
            [
                'source_district_id' => $getDistrictId('Rajshahi'),
                'destination_district_id' => $getDistrictId('Bogura'),
                'distance_km' => 70.00,
                'estimated_duration_minutes' => 90, // 1.5 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Bogura'),
                'destination_district_id' => $getDistrictId('Rajshahi'),
                'distance_km' => 70.00,
                'estimated_duration_minutes' => 90,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Rajshahi'),
                'destination_district_id' => $getDistrictId('Rangpur'),
                'distance_km' => 187.00,
                'estimated_duration_minutes' => 270, // 4.5 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Rangpur'),
                'destination_district_id' => $getDistrictId('Rajshahi'),
                'distance_km' => 187.00,
                'estimated_duration_minutes' => 270,
                'is_popular' => false,
                'is_active' => true,
            ],

            // Sylhet to other cities
            [
                'source_district_id' => $getDistrictId('Sylhet'),
                'destination_district_id' => $getDistrictId('Cumilla'),
                'distance_km' => 168.00,
                'estimated_duration_minutes' => 240, // 4 hours
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'source_district_id' => $getDistrictId('Cumilla'),
                'destination_district_id' => $getDistrictId('Sylhet'),
                'distance_km' => 168.00,
                'estimated_duration_minutes' => 240,
                'is_popular' => false,
                'is_active' => true,
            ],
        ];

        DB::table('routes')->insert($routes);
    }
}

