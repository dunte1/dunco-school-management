<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create staff table
if (!Schema::hasTable('staff')) {
    Schema::create('staff', function (Blueprint $table) {
        $table->id();
        $table->string('employee_id')->unique();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('phone')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->enum('gender', ['male', 'female', 'other'])->nullable();
        $table->text('address')->nullable();
        $table->string('position');
        $table->unsignedBigInteger('department_id');
        $table->date('hire_date');
        $table->decimal('salary', 10, 2)->nullable();
        $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
        $table->string('photo')->nullable();
        $table->timestamps();
    });
    echo "Staff table created successfully!\n";
}

// Create departments table
if (!Schema::hasTable('departments')) {
    Schema::create('departments', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->unsignedBigInteger('manager_id')->nullable();
        $table->timestamps();
    });
    echo "Departments table created successfully!\n";
}

// Create attendance table
if (!Schema::hasTable('attendance')) {
    Schema::create('attendance', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('staff_id');
        $table->date('date');
        $table->time('clock_in')->nullable();
        $table->time('clock_out')->nullable();
        $table->enum('status', ['present', 'absent', 'late', 'half_day'])->default('present');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
    echo "Attendance table created successfully!\n";
}

// Create payrolls table
if (!Schema::hasTable('payrolls')) {
    Schema::create('payrolls', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('staff_id');
        $table->string('month');
        $table->integer('year');
        $table->decimal('basic_salary', 10, 2);
        $table->decimal('allowances', 10, 2)->default(0);
        $table->decimal('deductions', 10, 2)->default(0);
        $table->decimal('net_salary', 10, 2);
        $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
        $table->date('payment_date')->nullable();
        $table->timestamps();
    });
    echo "Payrolls table created successfully!\n";
}

// Create contracts table
if (!Schema::hasTable('contracts')) {
    Schema::create('contracts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('staff_id');
        $table->string('contract_number')->unique();
        $table->string('position');
        $table->date('start_date');
        $table->date('end_date')->nullable();
        $table->enum('type', ['permanent', 'contract', 'probation', 'internship']);
        $table->decimal('salary', 10, 2);
        $table->text('terms')->nullable();
        $table->enum('status', ['active', 'expired', 'terminated'])->default('active');
        $table->unsignedBigInteger('approved_by')->nullable();
        $table->timestamps();
    });
    echo "Contracts table created successfully!\n";
}

// Create performance_reviews table
if (!Schema::hasTable('performance_reviews')) {
    Schema::create('performance_reviews', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('staff_id');
        $table->unsignedBigInteger('reviewer_id');
        $table->string('period'); // e.g., "Q1 2024", "Annual 2024"
        $table->date('review_date');
        $table->integer('overall_rating'); // 1-5 scale
        $table->text('strengths')->nullable();
        $table->text('weaknesses')->nullable();
        $table->text('goals')->nullable();
        $table->text('comments')->nullable();
        $table->enum('status', ['draft', 'submitted', 'approved', 'completed'])->default('draft');
        $table->timestamps();
    });
    echo "Performance reviews table created successfully!\n";
}

// Create leave_types table
if (!Schema::hasTable('leave_types')) {
    Schema::create('leave_types', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('default_days');
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    echo "Leave types table created successfully!\n";
}

echo "All HR tables check completed!\n";
