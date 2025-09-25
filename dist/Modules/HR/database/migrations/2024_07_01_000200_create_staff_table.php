<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->string('photo')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('gender');
            $table->date('dob')->nullable();
            $table->string('id_number')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('job_title')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->json('multi_school_ids')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('staff_id')->unique();
            $table->string('qr_code')->nullable();
            $table->string('barcode')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff');
    }
}; 