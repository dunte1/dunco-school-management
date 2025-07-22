<?php

namespace Modules\API\Http\Controllers;

use Illuminate\Routing\Controller;

class APIController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'APIController stub']);
    }
} 