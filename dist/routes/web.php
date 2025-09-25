<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Root route - show welcome page (must be first to avoid conflicts)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Admin\Config\RequiredDocumentController;
use App\Http\Controllers\Admin\Config\FeeConfigurationController;
use Modules\Finance\Http\Controllers\MpesaCallbackController;

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Attendance module views
    Route::view('/attendance/session-templates', 'modules.attendance.session_templates')->name('attendance.session_templates');
    Route::view('/attendance/past-records', 'modules.attendance.past_records')->name('attendance.past_records');
    Route::view('/attendance/biometric-logs', 'modules.attendance.biometric_logs')->name('attendance.biometric_logs');
    Route::view('/attendance/qr-logs', 'modules.attendance.qr_logs')->name('attendance.qr_logs');
    Route::view('/attendance/face-logs', 'modules.attendance.face_logs')->name('attendance.face_logs');
    Route::view('/attendance/acknowledgment-logs', 'modules.attendance.acknowledgment_logs')->name('attendance.acknowledgment_logs');
});

// Admin config routes
Route::prefix('admin/config')->middleware(['web', 'auth', 'admin'])->group(function () {
    // Required Documents
    Route::get('documents', [RequiredDocumentController::class, 'index'])->name('admin.config.documents.index');
    Route::post('documents', [RequiredDocumentController::class, 'store'])->name('admin.config.documents.store');
    Route::put('documents/{requiredDocument}', [RequiredDocumentController::class, 'update'])->name('admin.config.documents.update');
    Route::delete('documents/{requiredDocument}', [RequiredDocumentController::class, 'destroy'])->name('admin.config.documents.destroy');

    // Fee Configurations
    Route::get('fees', [FeeConfigurationController::class, 'index'])->name('admin.config.fees.index');
    Route::post('fees', [FeeConfigurationController::class, 'store'])->name('admin.config.fees.store');
    Route::put('fees/{feeConfiguration}', [FeeConfigurationController::class, 'update'])->name('admin.config.fees.update');
    Route::delete('fees/{feeConfiguration}', [FeeConfigurationController::class, 'destroy'])->name('admin.config.fees.destroy');
});

// Audit logs
Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->middleware(['auth', 'admin'])->name('audit_logs.index');

// Debug route for permission checking
Route::get('/debug/permissions', [App\Http\Controllers\DebugController::class, 'permissions'])
    ->middleware(['auth'])
    ->name('debug.permissions');

// Performance routes
Route::prefix('performance')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\PerformanceController::class, 'dashboard'])->name('performance.dashboard');
    Route::post('/optimize', [App\Http\Controllers\PerformanceController::class, 'optimize'])->name('performance.optimize');
    Route::get('/stats', [App\Http\Controllers\PerformanceController::class, 'stats'])->name('performance.stats');
    Route::post('/clear-caches', [App\Http\Controllers\PerformanceController::class, 'clearCaches'])->name('performance.clear-caches');
});

// Auth routes
require __DIR__.'/auth.php';

// Load module routes
require __DIR__.'/modules.php';

// Test route for Transport module
Route::get('/test-transport', function () {
    try {
        // Test if the class exists
        if (class_exists('\Modules\Transport\Http\Controllers\TransportController')) {
            return response()->json([
                'status' => 'success',
                'message' => 'Transport module class exists',
                'class' => '\Modules\Transport\Http\Controllers\TransportController'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Transport module class does not exist'
            ], 404);
        }
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Transport module error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('test.transport');

// Temporary route to seed Transport data
Route::get('/seed-transport', function () {
    try {
        // Create sample drivers
        $drivers = [
            [
                'name' => 'John Smith',
                'license_number' => 'DL001234',
                'license_expiry' => '2026-12-31',
                'phone' => '+1234567890',
                'email' => 'john.smith@school.com',
                'address' => '123 Main St, City',
                'date_of_birth' => '1985-05-15',
                'joining_date' => '2020-01-15',
                'salary' => 3500.00,
                'status' => 'active',
                'experience_years' => 5,
                'emergency_contact' => '+1234567891',
                'blood_group' => 'O+',
                'school_id' => 1
            ],
            [
                'name' => 'Mary Johnson',
                'license_number' => 'DL005678',
                'license_expiry' => '2026-12-31',
                'phone' => '+1234567892',
                'email' => 'mary.johnson@school.com',
                'address' => '456 Oak Ave, City',
                'date_of_birth' => '1988-08-22',
                'joining_date' => '2021-03-10',
                'salary' => 3200.00,
                'status' => 'active',
                'experience_years' => 3,
                'emergency_contact' => '+1234567893',
                'blood_group' => 'A+',
                'school_id' => 1
            ],
            [
                'name' => 'David Wilson',
                'license_number' => 'DL009012',
                'license_expiry' => '2026-12-31',
                'phone' => '+1234567894',
                'email' => 'david.wilson@school.com',
                'address' => '789 Pine Rd, City',
                'date_of_birth' => '1982-12-05',
                'joining_date' => '2018-07-20',
                'salary' => 3800.00,
                'status' => 'active',
                'experience_years' => 7,
                'emergency_contact' => '+1234567895',
                'blood_group' => 'B+',
                'school_id' => 1
            ]
        ];

        foreach ($drivers as $driverData) {
            \Modules\Transport\Models\Driver::create($driverData);
        }

        // Create sample vehicles
        $vehicles = [
            [
                'vehicle_number' => 'BUS001',
                'vehicle_type' => 'bus',
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
                'fuel_type' => 'diesel',
                'mileage' => 25000,
                'description' => 'Large school bus for main routes',
                'school_id' => 1
            ],
            [
                'vehicle_number' => 'BUS002',
                'vehicle_type' => 'minibus',
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
                'fuel_type' => 'petrol',
                'mileage' => 15000,
                'description' => 'Small bus for special routes',
                'school_id' => 1
            ],
            [
                'vehicle_number' => 'BUS003',
                'vehicle_type' => 'bus',
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
                'fuel_type' => 'diesel',
                'mileage' => 35000,
                'description' => 'Standard school bus',
                'school_id' => 1
            ]
        ];

        foreach ($vehicles as $vehicleData) {
            \Modules\Transport\Models\Vehicle::create($vehicleData);
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
            \Modules\Transport\Models\Route::create($routeData);
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
            \Modules\Transport\Models\Trip::create($tripData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transport data seeded successfully',
            'data' => [
                'drivers' => 3,
                'vehicles' => 3,
                'routes' => 3,
                'trips' => 3
            ]
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error seeding data: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('seed.transport');
