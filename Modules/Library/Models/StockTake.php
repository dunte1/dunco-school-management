<?php

namespace Modules\Library\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StockTake extends Model
{
    protected $table = 'library_stock_takes';

    protected $fillable = [
        'library_id','started_by','started_at','completed_at','status','notes'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user who started the stock take.
     */
    public function startedBy()
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    /**
     * Get the items in this stock take.
     */
    public function items()
    {
        return $this->hasMany(StockTakeItem::class, 'stock_take_id');
    }
}