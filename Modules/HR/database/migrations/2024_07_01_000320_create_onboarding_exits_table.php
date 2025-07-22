<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('onboarding_exits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->json('onboarding_checklist')->nullable();
            $table->json('induction_checklist')->nullable();
            $table->json('exit_checklist')->nullable();
            $table->date('exit_date')->nullable();
            $table->string('exit_reason')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('onboarding_exits');
    }
}; 