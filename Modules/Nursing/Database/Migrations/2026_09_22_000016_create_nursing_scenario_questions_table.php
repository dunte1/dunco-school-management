<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_scenario_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scenario_id')->constrained('nursing_scenarios')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->text('question');
            $table->json('choices');
            $table->integer('correct_choice_index');
            $table->text('explanation')->nullable();
            $table->text('learning_point')->nullable();
            $table->timestamps();

            $table->index(['scenario_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_scenario_questions');
    }
};
