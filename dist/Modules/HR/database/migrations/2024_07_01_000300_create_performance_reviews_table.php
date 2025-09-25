<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->string('period'); // e.g., 2024-Q1, 2024-Annual
            $table->json('criteria')->nullable();
            $table->integer('score')->nullable();
            $table->date('review_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('type')->nullable(); // Appraisal, Disciplinary, Award, etc.
            $table->string('award')->nullable();
            $table->string('disciplinary_action')->nullable();
            $table->date('action_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('performance_reviews');
    }
}; 