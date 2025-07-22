<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->string('category'); // e.g. Tuition, Boarding, Activity
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('unpaid'); // unpaid, paid, partial
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_fees');
    }
}; 