<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedTransportSample extends Command
{
    protected $signature = 'transport:seed-sample {--force : Seed even if transport records already exist}';

    protected $description = 'Seed sample transport data (drivers, vehicles, routes, trips) for demo/testing';

    public function handle(): int
    {
        if (app()->environment('production') && !$this->option('force')) {
            $this->error('Refusing to seed sample data in production without --force.');

            return self::FAILURE;
        }

        $driverModel = \Modules\Transport\Models\Driver::class;
        $vehicleModel = \Modules\Transport\Models\Vehicle::class;
        $routeModel = \Modules\Transport\Models\Route::class;
        $tripModel = \Modules\Transport\Models\Trip::class;

        if (!class_exists($driverModel)) {
            $this->error('Transport module models were not found.');

            return self::FAILURE;
        }

        if ($driverModel::query()->exists() && !$this->option('force')) {
            $this->warn('Transport records already exist. Re-run with --force to seed duplicates.');

            return self::SUCCESS;
        }

        $schoolId = DB::table('schools')->value('id') ?? 1;

        try {
            DB::transaction(function () use ($driverModel, $vehicleModel, $routeModel, $tripModel, $schoolId) {
                $drivers = [
                    ['name' => 'John Smith', 'license_number' => 'DL001234', 'license_expiry' => '2026-12-31', 'phone' => '+1234567890', 'email' => 'john.smith@school.com', 'address' => '123 Main St, City', 'date_of_birth' => '1985-05-15', 'joining_date' => '2020-01-15', 'salary' => 3500.00, 'status' => 'active', 'experience_years' => 5, 'emergency_contact' => '+1234567891', 'blood_group' => 'O+', 'school_id' => $schoolId],
                    ['name' => 'Mary Johnson', 'license_number' => 'DL005678', 'license_expiry' => '2026-12-31', 'phone' => '+1234567892', 'email' => 'mary.johnson@school.com', 'address' => '456 Oak Ave, City', 'date_of_birth' => '1988-08-22', 'joining_date' => '2021-03-10', 'salary' => 3200.00, 'status' => 'active', 'experience_years' => 3, 'emergency_contact' => '+1234567893', 'blood_group' => 'A+', 'school_id' => $schoolId],
                    ['name' => 'David Wilson', 'license_number' => 'DL009012', 'license_expiry' => '2026-12-31', 'phone' => '+1234567894', 'email' => 'david.wilson@school.com', 'address' => '789 Pine Rd, City', 'date_of_birth' => '1982-12-05', 'joining_date' => '2018-07-20', 'salary' => 3800.00, 'status' => 'active', 'experience_years' => 7, 'emergency_contact' => '+1234567895', 'blood_group' => 'B+', 'school_id' => $schoolId],
                ];
                foreach ($drivers as $driver) {
                    $driverModel::create($driver);
                }

                $vehicles = [
                    ['vehicle_number' => 'BUS001', 'vehicle_type' => 'bus', 'brand' => 'Blue Bird', 'model' => 'Vision', 'year' => 2020, 'capacity' => 50, 'driver_id' => 1, 'status' => 'active', 'registration_number' => 'REG001', 'insurance_expiry' => '2025-12-31', 'fitness_expiry' => '2025-06-30', 'permit_expiry' => '2025-12-31', 'fuel_type' => 'diesel', 'mileage' => 25000, 'description' => 'Large school bus for main routes', 'school_id' => $schoolId],
                    ['vehicle_number' => 'BUS002', 'vehicle_type' => 'minibus', 'brand' => 'Ford', 'model' => 'Transit', 'year' => 2021, 'capacity' => 25, 'driver_id' => 2, 'status' => 'active', 'registration_number' => 'REG002', 'insurance_expiry' => '2025-12-31', 'fitness_expiry' => '2025-06-30', 'permit_expiry' => '2025-12-31', 'fuel_type' => 'petrol', 'mileage' => 15000, 'description' => 'Small bus for special routes', 'school_id' => $schoolId],
                    ['vehicle_number' => 'BUS003', 'vehicle_type' => 'bus', 'brand' => 'Thomas', 'model' => 'Saf-T-Liner', 'year' => 2019, 'capacity' => 45, 'driver_id' => 3, 'status' => 'maintenance', 'registration_number' => 'REG003', 'insurance_expiry' => '2025-12-31', 'fitness_expiry' => '2025-06-30', 'permit_expiry' => '2025-12-31', 'fuel_type' => 'diesel', 'mileage' => 35000, 'description' => 'Standard school bus', 'school_id' => $schoolId],
                ];
                foreach ($vehicles as $vehicle) {
                    $vehicleModel::create($vehicle);
                }

                $routes = [
                    ['name' => 'North Route', 'description' => 'Route covering northern areas', 'start_location' => 'School', 'end_location' => 'North Terminal', 'distance' => 15.5, 'estimated_time' => 45, 'status' => 'active', 'school_id' => $schoolId],
                    ['name' => 'South Route', 'description' => 'Route covering southern areas', 'start_location' => 'School', 'end_location' => 'South Terminal', 'distance' => 12.3, 'estimated_time' => 35, 'status' => 'active', 'school_id' => $schoolId],
                    ['name' => 'East Route', 'description' => 'Route covering eastern areas', 'start_location' => 'School', 'end_location' => 'East Terminal', 'distance' => 18.7, 'estimated_time' => 55, 'status' => 'active', 'school_id' => $schoolId],
                ];
                foreach ($routes as $route) {
                    $routeModel::create($route);
                }

                $trips = [
                    ['vehicle_id' => 1, 'driver_id' => 1, 'route_id' => 1, 'trip_date' => now()->toDateString(), 'start_time' => '07:00:00', 'end_time' => '08:30:00', 'status' => 'completed', 'passenger_count' => 35, 'distance_covered' => 15.5, 'fuel_consumed' => 8.5, 'notes' => 'Morning pickup completed successfully', 'school_id' => $schoolId],
                    ['vehicle_id' => 2, 'driver_id' => 2, 'route_id' => 2, 'trip_date' => now()->toDateString(), 'start_time' => '07:15:00', 'end_time' => '08:45:00', 'status' => 'completed', 'passenger_count' => 22, 'distance_covered' => 12.3, 'fuel_consumed' => 6.2, 'notes' => 'Morning pickup completed successfully', 'school_id' => $schoolId],
                    ['vehicle_id' => 1, 'driver_id' => 1, 'route_id' => 1, 'trip_date' => now()->addDay()->toDateString(), 'start_time' => '07:00:00', 'end_time' => null, 'status' => 'scheduled', 'passenger_count' => 0, 'distance_covered' => 0, 'fuel_consumed' => 0, 'notes' => 'Scheduled for tomorrow', 'school_id' => $schoolId],
                ];
                foreach ($trips as $trip) {
                    $tripModel::create($trip);
                }
            });
        } catch (\Throwable $e) {
            $this->error('Error seeding transport data: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Transport sample data seeded successfully (3 drivers, 3 vehicles, 3 routes, 3 trips).');

        return self::SUCCESS;
    }
}
