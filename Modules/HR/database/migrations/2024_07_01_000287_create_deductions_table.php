<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['tax', 'pension', 'insurance', 'loan', 'other'])->default('other');
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('calculation_type', ['fixed', 'percentage'])->default('fixed');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();
            
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('deductions');
    }
};
