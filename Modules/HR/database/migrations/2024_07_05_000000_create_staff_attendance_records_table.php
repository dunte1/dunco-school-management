<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('staff_attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('staff_id');
            $table->date('date');
            $table->string('status', 20); // present, absent, late, excused, sick, on_leave, suspended
            $table->string('remarks', 255)->nullable();
            $table->unsignedBigInteger('marked_by')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'staff_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff_attendance_records');
    }
}; 