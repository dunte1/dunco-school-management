<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Seed vehicle maintenance records
        DB::table('vehicle_maintenances')->insert([
            [
                'vehicle_id' => 1,
                'type' => 'service',
                'date' => now()->subDays(20)->toDateString(),
                'cost' => 150.00,
                'odometer' => 25000,
                'notes' => 'Oil change and filter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 2,
                'type' => 'tires',
                'date' => now()->subDays(10)->toDateString(),
                'cost' => 320.00,
                'odometer' => 18000,
                'notes' => 'Front tires replaced',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed fuel logs
        DB::table('fuel_logs')->insert([
            [
                'vehicle_id' => 1,
                'date' => now()->subDays(7)->toDateString(),
                'liters' => 45.50,
                'cost' => 68.25,
                'odometer' => 25500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 1,
                'date' => now()->subDays(2)->toDateString(),
                'liters' => 40.00,
                'cost' => 60.00,
                'odometer' => 26020,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_id' => 2,
                'date' => now()->subDays(5)->toDateString(),
                'liters' => 35.00,
                'cost' => 52.50,
                'odometer' => 18550,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}


