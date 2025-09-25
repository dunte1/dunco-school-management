<?php

namespace Modules\Transport\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Transport\Models\Driver;
use Modules\Transport\Models\Vehicle;
use Modules\Transport\Models\Route;
use Modules\Transport\Models\Trip;

class TransportDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample drivers
        $drivers = [
            [
                'name' => 'John Smith',
                'license_number' => 'DL001234',
                'phone' => '+1234567890',
                'email' => 'john.smith@school.com',
                'address' => '123 Main St, City',
                'status' => 'active',
                'experience_years' => 5,
                'emergency_contact' => '+1234567891',
                'school_id' => 1
            ],
            [
                'name' => 'Mary Johnson',
                'license_number' => 'DL005678',
                'phone' => '+1234567892',
                'email' => 'mary.johnson@school.com',
                'address' => '456 Oak Ave, City',
                'status' => 'active',
                'experience_years' => 3,
                'emergency_contact' => '+1234567893',
                'school_id' => 1
            ],
            [
                'name' => 'David Wilson',
                'license_number' => 'DL009012',
                'phone' => '+1234567894',
                'email' => 'david.wilson@school.com',
                'address' => '789 Pine Rd, City',
                'status' => 'active',
                'experience_years' => 7,
                'emergency_contact' => '+1234567895',
                'school_id' => 1
            ]
        ];

        foreach ($drivers as $driverData) {
            Driver::create($driverData);
        }

        // Create sample vehicles
        $vehicles = [
            [
                'vehicle_number' => 'BUS001',
                'vehicle_type' => 'School Bus',
                'brand' => 'Blue Bird',
                'model' => 'Vision',
                'year' => 2020,
                'capacity' => 50,
                'driver_id' => 1,
                'status' => 'active',
                'registration_number' => 'REG001',
                'insurance_expiry' => '2025-12-31',
                'fitness_expiry' => '2025-06-30',
                'permit_expiry' => '2025-12-31',
                'fuel_type' => 'Diesel',
                'mileage' => 25000,
                'description' => 'Large school bus for main routes',
                'school_id' => 1
            ],
            [
                'vehicle_number' => 'BUS002',
                'vehicle_type' => 'Mini Bus',
                'brand' => 'Ford',
                'model' => 'Transit',
                'year' => 2021,
                'capacity' => 25,
                'driver_id' => 2,
                'status' => 'active',
                'registration_number' => 'REG002',
                'insurance_expiry' => '2025-12-31',
                'fitness_expiry' => '2025-06-30',
                'permit_expiry' => '2025-12-31',
                'fuel_type' => 'Petrol',
                'mileage' => 15000,
                'description' => 'Small bus for special routes',
                'school_id' => 1
            ],
            [
                'vehicle_number' => 'BUS003',
                'vehicle_type' => 'School Bus',
                'brand' => 'Thomas',
                'model' => 'Saf-T-Liner',
                'year' => 2019,
                'capacity' => 45,
                'driver_id' => 3,
                'status' => 'maintenance',
                'registration_number' => 'REG003',
                'insurance_expiry' => '2025-12-31',
                'fitness_expiry' => '2025-06-30',
                'permit_expiry' => '2025-12-31',
                'fuel_type' => 'Diesel',
                'mileage' => 35000,
                'description' => 'Standard school bus',
                'school_id' => 1
            ]
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::create($vehicleData);
        }

        // Create sample routes
        $routes = [
            [
                'name' => 'North Route',
                'description' => 'Route covering northern areas',
                'start_location' => 'School',
                'end_location' => 'North Terminal',
                'distance' => 15.5,
                'estimated_time' => 45,
                'status' => 'active',
                'school_id' => 1
            ],
            [
                'name' => 'South Route',
                'description' => 'Route covering southern areas',
                'start_location' => 'School',
                'end_location' => 'South Terminal',
                'distance' => 12.3,
                'estimated_time' => 35,
                'status' => 'active',
                'school_id' => 1
            ],
            [
                'name' => 'East Route',
                'description' => 'Route covering eastern areas',
                'start_location' => 'School',
                'end_location' => 'East Terminal',
                'distance' => 18.7,
                'estimated_time' => 55,
                'status' => 'active',
                'school_id' => 1
            ]
        ];

        foreach ($routes as $routeData) {
            Route::create($routeData);
        }

        // Create sample trips
        $trips = [
            [
                'vehicle_id' => 1,
                'driver_id' => 1,
                'route_id' => 1,
                'trip_date' => now()->toDateString(),
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'status' => 'completed',
                'passenger_count' => 35,
                'distance_covered' => 15.5,
                'fuel_consumed' => 8.5,
                'notes' => 'Morning pickup completed successfully',
                'school_id' => 1
            ],
            [
                'vehicle_id' => 2,
                'driver_id' => 2,
                'route_id' => 2,
                'trip_date' => now()->toDateString(),
                'start_time' => '07:15:00',
                'end_time' => '08:45:00',
                'status' => 'completed',
                'passenger_count' => 22,
                'distance_covered' => 12.3,
                'fuel_consumed' => 6.2,
                'notes' => 'Morning pickup completed successfully',
                'school_id' => 1
            ],
            [
                'vehicle_id' => 1,
                'driver_id' => 1,
                'route_id' => 1,
                'trip_date' => now()->addDay()->toDateString(),
                'start_time' => '07:00:00',
                'end_time' => null,
                'status' => 'scheduled',
                'passenger_count' => 0,
                'distance_covered' => 0,
                'fuel_consumed' => 0,
                'notes' => 'Scheduled for tomorrow',
                'school_id' => 1
            ]
        ];

        foreach ($trips as $tripData) {
            Trip::create($tripData);
        }
    }
}
