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
        Schema::table('books', function (Blueprint $table) {
            // Add new columns first
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->string('edition')->nullable();
            $table->integer('year')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['available', 'borrowed', 'reserved', 'lost'])->default('available');
            $table->string('ebook_file_path')->nullable();
        });

        // Add foreign key constraints after columns exist
        Schema::table('books', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
        });

        // Drop old columns after new ones are added
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['author', 'is_available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['author_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['publisher_id']);
            
            // Drop added columns
            $table->dropColumn([
                'author_id', 'category_id', 'publisher_id',
                'barcode', 'edition', 'year', 'cover_image', 
                'status', 'ebook_file_path'
            ]);
            
            // Restore original columns
            $table->string('author')->nullable();
            $table->boolean('is_available')->default(true);
        });
    }
};
