<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('library_stock_takes')) {
        Schema::create('library_stock_takes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('library_id')->nullable()->index();
            $table->unsignedBigInteger('started_by')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['draft','in_progress','completed','posted'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        }

        if (!Schema::hasTable('library_stock_take_items')) {
        Schema::create('library_stock_take_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_take_id')->index();
            $table->unsignedBigInteger('book_id')->index();
            $table->integer('expected_qty')->default(0);
            $table->integer('counted_qty')->default(0);
            $table->integer('variance')->default(0);
            $table->timestamps();
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('library_stock_take_items');
        Schema::dropIfExists('library_stock_takes');
    }
};


