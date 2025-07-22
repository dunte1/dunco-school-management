<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->string('type'); // Permanent, Contract, Probation, etc.
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('duration_months')->nullable();
            $table->boolean('on_probation')->default(false);
            $table->date('probation_end')->nullable();
            $table->date('renewal_reminder')->nullable();
            $table->string('promotion_from')->nullable();
            $table->string('promotion_to')->nullable();
            $table->date('promotion_date')->nullable();
            $table->decimal('old_salary', 12, 2)->nullable();
            $table->decimal('new_salary', 12, 2)->nullable();
            $table->unsignedBigInteger('transfer_from_school')->nullable();
            $table->unsignedBigInteger('transfer_to_school')->nullable();
            $table->unsignedBigInteger('transfer_from_department')->nullable();
            $table->unsignedBigInteger('transfer_to_department')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}; 