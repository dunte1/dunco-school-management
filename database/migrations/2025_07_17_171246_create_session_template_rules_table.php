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
        Schema::create('session_template_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_template_id');
            $table->string('rule_type'); // e.g., grace_period, late_threshold
            $table->string('value'); // value for the rule
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('session_template_id')->references('id')->on('session_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_template_rules');
    }
};
