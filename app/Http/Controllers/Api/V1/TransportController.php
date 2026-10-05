<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransportController extends Controller
{
    public function fuelSummary($vehicleId)
    {
        $totalLiters = \Modules\Transport\Models\FuelLog::where('vehicle_id',$vehicleId)->sum('liters');
        $totalCost = \Modules\Transport\Models\FuelLog::where('vehicle_id',$vehicleId)->sum('cost');
        return response()->json(['liters' => (float)$totalLiters, 'cost' => (float)$totalCost]);
    }
}


