<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;

class StockTakeItem extends Model
{
    protected $table = 'library_stock_take_items';

    protected $fillable = [
        'stock_take_id','book_id','expected_qty','counted_qty','variance'
    ];

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $item->variance = (int)$item->counted_qty - (int)$item->expected_qty;
        });
    }
}


