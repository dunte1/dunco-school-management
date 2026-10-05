<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\FuelLog;

class FuelLogController extends Controller
{
    public function index()
    {
        return view('transport::fuel.index', [
            'logs' => FuelLog::orderByDesc('date')->paginate(20)
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => ['required','integer'],
            'date' => ['required','date'],
            'liters' => ['required','numeric','min:0'],
            'cost' => ['required','numeric','min:0'],
            'odometer' => ['nullable','integer','min:0'],
        ]);
        FuelLog::create($data);
        return back()->with('success','Fuel log saved');
    }
}


