<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\VehicleMaintenance;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('transport::maintenance.index', [
            'records' => VehicleMaintenance::orderByDesc('date')->paginate(20)
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => ['required','integer'],
            'type' => ['required','string','max:50'],
            'date' => ['required','date'],
            'cost' => ['required','numeric','min:0'],
            'odometer' => ['nullable','integer','min:0'],
            'notes' => ['nullable','string']
        ]);
        VehicleMaintenance::create($data);
        return back()->with('success','Maintenance saved');
    }
}


