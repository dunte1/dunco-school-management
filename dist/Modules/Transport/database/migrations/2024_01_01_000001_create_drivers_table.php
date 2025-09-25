<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->date('date_of_birth');
            $table->date('joining_date');
            $table->decimal('salary', 10, 2);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('emergency_contact')->nullable();
            $table->string('blood_group')->nullable();
            $table->integer('experience_years')->default(0);
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}; 