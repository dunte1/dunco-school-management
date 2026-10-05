<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('transport_students')) {
            return;
        }
        
        Schema::create('transport_students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('student_id')->unique();
            $table->string('parent_name');
            $table->string('parent_phone', 20);
            $table->string('parent_email')->nullable();
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->time('pickup_time');
            $table->time('dropoff_time');
            $table->decimal('monthly_fee', 10, 2);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone', 20)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->timestamps();

            $table->index(['status', 'route_id']);
            $table->index(['status', 'vehicle_id']);
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_students');
    }
};
