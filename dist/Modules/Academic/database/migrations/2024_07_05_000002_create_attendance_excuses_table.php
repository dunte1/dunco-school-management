<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('attendance_excuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->date('date');
            $table->string('period', 32)->nullable();
            $table->string('reason', 255);
            $table->string('document', 255)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'staff_id', 'date', 'period', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_excuses');
    }
}; 