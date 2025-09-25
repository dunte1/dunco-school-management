<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::create('fee_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 12, 2);
            $table->json('applies_to')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fee_configurations');
    }
} 