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

            // Reverse routes (return journeys)
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
        ];

        DB::table('routes')->insert($routes);
    }
}

