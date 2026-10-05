<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\Models\StockTake;
use Modules\Library\Models\StockTakeItem;

class StockTakeController extends Controller
{
    public function wizard()
    {
        return view('library::stocktake.wizard');
    }

    public function start(Request $request)
    {
        $stockTake = StockTake::create([
            'library_id' => $request->input('library_id'),
            'started_by' => $request->user()?->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);
        return response()->json($stockTake);
    }

    public function scan(Request $request, StockTake $stockTake)
    {
        $data = $request->validate([
            'book_id' => ['required','integer'],
            'expected_qty' => ['nullable','integer'],
            'counted_qty' => ['required','integer','min:0'],
        ]);
        $item = StockTakeItem::updateOrCreate(
            ['stock_take_id' => $stockTake->id, 'book_id' => $data['book_id']],
            ['expected_qty' => $data['expected_qty'] ?? 0, 'counted_qty' => $data['counted_qty']]
        );
        return response()->json($item);
    }

    public function variance(StockTake $stockTake)
    {
        $items = StockTakeItem::where('stock_take_id', $stockTake->id)->get();
        return response()->json([
            'stock_take' => $stockTake,
            'items' => $items,
            'total_positive' => $items->where('variance','>',0)->sum('variance'),
            'total_negative' => $items->where('variance','<',0)->sum('variance'),
        ]);
    }

    public function postAdjustments(StockTake $stockTake)
    {
        $stockTake->status = 'posted';
        $stockTake->completed_at = now();
        $stockTake->save();
        // TODO: Adjust book inventory based on variances
        return response()->json(['posted' => true]);
    }
}


