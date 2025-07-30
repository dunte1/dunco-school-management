<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Hostel\Models\Warden;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Http\Requests\StoreWardenRequest;
use Modules\Hostel\Http\Requests\UpdateWardenRequest;
use App\Http\Controllers\Controller;

class WardenController extends Controller
{
    public function index() { /* List all wardens */ }
    public function create() { /* Show form to create warden */ }
    public function store(Request $request) { /* Store new warden */ }
    public function show(Warden $warden) { /* Show single warden */ }
    public function edit(Warden $warden) { /* Show form to edit warden */ }
    public function update(Request $request, Warden $warden) { /* Update warden */ }
    public function destroy(Warden $warden) { /* Delete warden */ }
} 