<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibraryDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create a stock-take with items
        $stockTakeId = DB::table('library_stock_takes')->insertGetId([
            'library_id' => 1,
            'started_by' => 1,
            'started_at' => now()->subDay(),
            'status' => 'in_progress',
            'notes' => 'Quarterly stock check',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('library_stock_take_items')->insert([
            [
                'stock_take_id' => $stockTakeId,
                'book_id' => 1001,
                'expected_qty' => 10,
                'counted_qty' => 9,
                'variance' => -1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'stock_take_id' => $stockTakeId,
                'book_id' => 1002,
                'expected_qty' => 5,
                'counted_qty' => 6,
                'variance' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}


