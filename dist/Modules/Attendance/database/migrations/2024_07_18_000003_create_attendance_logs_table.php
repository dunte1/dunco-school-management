<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_record_id')->constrained('academic_attendance_records')->onDelete('cascade');
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->string('action'); // created, updated, deleted
            $table->text('changes')->nullable(); // JSON or text description
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_logs');
    }
}; 