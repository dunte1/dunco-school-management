<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('academic_student_parent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->string('relationship');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->unique(['student_id', 'parent_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_student_parent');
    }
}; 